<?php

namespace app\models\repositories\reservas_repository;

use app\models\database\DatabaseInterface;
use app\models\entities\Reserva;
use app\models\mappers\ReservaMappers;
use Exception;

class ReservasRepository implements ReservasRepositoryInterface
{
    private const SELECT_BASE = "SELECT r.*,
                c.nombre AS cliente_nombre,
                CONCAT(v.marca, ' ', v.modelo, ' (', v.anio, ')') AS vehiculo_descripcion
            FROM reservas r
            INNER JOIN clientes c ON c.id = r.cliente_id
            INNER JOIN vehiculos v ON v.id = r.vehiculo_id";

    private DatabaseInterface $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function get_reservas(): array
    {
        $rows = $this->db->query_select(self::SELECT_BASE . " ORDER BY r.fecha_inicio DESC, r.id DESC");

        return array_map(
            fn(array $row): Reserva => ReservaMappers::to_entity($row),
            $rows
        );
    }

    public function get_reservas_activas(): array
    {
        $rows = $this->db->query_select(
            self::SELECT_BASE . " WHERE r.estado = 'activa' ORDER BY r.fecha_inicio"
        );

        return array_map(
            fn(array $row): Reserva => ReservaMappers::to_entity($row),
            $rows
        );
    }

    public function find_by_id(int $id): Reserva
    {
        $rows = $this->db->query_select(self::SELECT_BASE . " WHERE r.id = ?", [$id]);

        if (count($rows) === 0) {
            throw new Exception("Reserva no encontrada", 2);
        }

        return ReservaMappers::to_entity($rows[0]);
    }

    public function save(Reserva $reserva): int
    {
        $sql = "INSERT INTO reservas (cliente_id, vehiculo_id, fecha_inicio, fecha_fin, estado)
                VALUES (?, ?, ?, ?, ?)";

        $this->db->query($sql, [
            $reserva->get_cliente_id(),
            $reserva->get_vehiculo_id(),
            $reserva->get_fecha_inicio(),
            $reserva->get_fecha_fin(),
            $reserva->get_estado()
        ]);

        return $this->db->last_insert_id();
    }

    public function update(Reserva $reserva): bool
    {
        $sql = "UPDATE reservas
                SET cliente_id = ?, vehiculo_id = ?, fecha_inicio = ?, fecha_fin = ?, estado = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $reserva->get_cliente_id(),
            $reserva->get_vehiculo_id(),
            $reserva->get_fecha_inicio(),
            $reserva->get_fecha_fin(),
            $reserva->get_estado(),
            $reserva->get_id()
        ]);
    }

    public function update_estado(int $id, string $estado): bool
    {
        return $this->db->query(
            "UPDATE reservas SET estado = ? WHERE id = ?",
            [$estado, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->query("DELETE FROM reservas WHERE id = ?", [$id]);
    }

    public function hay_solapamiento(int $vehiculo_id, string $inicio, string $fin, int $excluir_id = 0): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM reservas
                WHERE vehiculo_id = ?
                AND estado = 'activa'
                AND fecha_inicio <= ?
                AND fecha_fin >= ?";
        $params = [$vehiculo_id, $fin, $inicio];

        if ($excluir_id > 0) {
            $sql .= " AND id <> ?";
            $params[] = $excluir_id;
        }

        $rows = $this->db->query_select($sql, $params);

        return (int) ($rows[0]["total"] ?? 0) > 0;
    }

    public function historial_por_vehiculo(int $vehiculo_id): array
    {
        $rows = $this->db->query_select(
            self::SELECT_BASE . " WHERE r.vehiculo_id = ? ORDER BY r.fecha_inicio DESC",
            [$vehiculo_id]
        );

        return array_map(
            fn(array $row): Reserva => ReservaMappers::to_entity($row),
            $rows
        );
    }

    public function historial_por_cliente(int $cliente_id): array
    {
        $rows = $this->db->query_select(
            self::SELECT_BASE . " WHERE r.cliente_id = ? ORDER BY r.fecha_inicio DESC",
            [$cliente_id]
        );

        return array_map(
            fn(array $row): Reserva => ReservaMappers::to_entity($row),
            $rows
        );
    }

    public function contar_por_estado(string $estado): int
    {
        $rows = $this->db->query_select(
            "SELECT COUNT(*) AS total FROM reservas WHERE estado = ?",
            [$estado]
        );

        return (int) ($rows[0]["total"] ?? 0);
    }
}
