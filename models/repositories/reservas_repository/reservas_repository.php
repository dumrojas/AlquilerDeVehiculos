<?php

namespace app\models\repositories\reservas_repository;

use app\models\database\DatabaseInterface;
use app\models\entities\Reserva;
use app\models\mappers\ReservaMappers;
use Exception;

class ReservasRepository implements ReservasRepositoryInterface
{
    private const SELECT_BASE = "select r.*,
                c.nombre as cliente_nombre,
                concat(v.marca, ' ', v.modelo, ' (', v.anio, ')') as vehiculo_descripcion
            from reservas r
            inner join clientes c on c.id = r.cliente_id
            inner join vehiculos v on v.id = r.vehiculo_id";

    private DatabaseInterface $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function get_reservas(): array
    {
        $sql = self::SELECT_BASE . " order by r.fecha_inicio desc, r.id desc";
        $rows = $this->db->query_select($sql);

        return array_map(
            fn(array $row): Reserva => ReservaMappers::to_entity($row),
            $rows
        );
    }

    public function get_reservas_activas(): array
    {
        $sql = self::SELECT_BASE . " where r.estado = 'activa' order by r.fecha_inicio";
        $rows = $this->db->query_select($sql);

        return array_map(
            fn(array $row): Reserva => ReservaMappers::to_entity($row),
            $rows
        );
    }

    public function find_by_id(int $id): Reserva
    {
        $sql = self::SELECT_BASE . " where r.id=$id";
        $rows = $this->db->query_select($sql);

        if (count($rows) === 0) {
            throw new Exception("Reserva no encontrada", 2);
        }

        return ReservaMappers::to_entity($rows[0]);
    }

    public function save(Reserva $reserva): bool
    {
        $sql = "insert into reservas (cliente_id, vehiculo_id, fecha_inicio, fecha_fin, estado) values";
        $sql .= "(" . $reserva->get_cliente_id() . "," . $reserva->get_vehiculo_id() . ",";
        $sql .= "'" . $reserva->get_fecha_inicio() . "','" . $reserva->get_fecha_fin() . "',";
        $sql .= "'" . $reserva->get_estado() . "')";

        return $this->db->query($sql);
    }

    public function update(Reserva $reserva): bool
    {
        $sql = "update reservas set ";
        $sql .= "cliente_id=" . $reserva->get_cliente_id() . ", ";
        $sql .= "vehiculo_id=" . $reserva->get_vehiculo_id() . ", ";
        $sql .= "fecha_inicio='" . $reserva->get_fecha_inicio() . "', ";
        $sql .= "fecha_fin='" . $reserva->get_fecha_fin() . "', ";
        $sql .= "estado='" . $reserva->get_estado() . "' ";
        $sql .= "where id=" . $reserva->get_id();

        return $this->db->query($sql);
    }

    public function update_estado(int $id, string $estado): bool
    {
        $sql = "update reservas set estado='$estado' where id=$id";

        return $this->db->query($sql);
    }

    public function delete(int $id): bool
    {
        $sql = "delete from reservas where id=$id";

        return $this->db->query($sql);
    }

    public function hay_solapamiento(int $vehiculo_id, string $inicio, string $fin, int $excluir_id = 0): bool
    {
        $sql = "select count(*) as total from reservas
                where vehiculo_id=$vehiculo_id
                and estado = 'activa'
                and fecha_inicio <= '$fin'
                and fecha_fin >= '$inicio'";

        if ($excluir_id > 0) {
            $sql .= " and id <> $excluir_id";
        }

        $rows = $this->db->query_select($sql);

        return (int) ($rows[0]["total"] ?? 0) > 0;
    }

    public function historial_por_vehiculo(int $vehiculo_id): array
    {
        $sql = self::SELECT_BASE . " where r.vehiculo_id=$vehiculo_id order by r.fecha_inicio desc";
        $rows = $this->db->query_select($sql);

        return array_map(
            fn(array $row): Reserva => ReservaMappers::to_entity($row),
            $rows
        );
    }

    public function historial_por_cliente(int $cliente_id): array
    {
        $sql = self::SELECT_BASE . " where r.cliente_id=$cliente_id order by r.fecha_inicio desc";
        $rows = $this->db->query_select($sql);

        return array_map(
            fn(array $row): Reserva => ReservaMappers::to_entity($row),
            $rows
        );
    }

    public function contar_por_estado(string $estado): int
    {
        $sql = "select count(*) as total from reservas where estado='$estado'";
        $rows = $this->db->query_select($sql);

        return (int) ($rows[0]["total"] ?? 0);
    }
}
