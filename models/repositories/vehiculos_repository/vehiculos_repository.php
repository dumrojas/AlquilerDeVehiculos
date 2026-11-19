<?php

namespace app\models\repositories\vehiculos_repository;

use app\models\database\DatabaseInterface;
use app\models\entities\Vehiculo;
use app\models\mappers\VehiculoMappers;
use Exception;

class VehiculosRepository implements VehiculosRepositoryInterface
{
    private DatabaseInterface $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function get_vehiculos(): array
    {
        $rows = $this->db->query_select("SELECT * FROM vehiculos ORDER BY marca, modelo");

        return array_map(
            fn(array $row): Vehiculo => VehiculoMappers::to_entity($row),
            $rows
        );
    }

    public function get_vehiculos_disponibles(): array
    {
        $sql = "SELECT v.* FROM vehiculos v
                WHERE v.estado = 'disponible'
                AND NOT EXISTS (
                    SELECT 1 FROM reservas r
                    WHERE r.vehiculo_id = v.id
                    AND r.estado = 'activa'
                    AND CURDATE() BETWEEN r.fecha_inicio AND r.fecha_fin
                )
                ORDER BY v.marca, v.modelo";

        $rows = $this->db->query_select($sql);

        return array_map(
            fn(array $row): Vehiculo => VehiculoMappers::to_entity($row),
            $rows
        );
    }

    public function find_by_id(int $id): Vehiculo
    {
        $rows = $this->db->query_select("SELECT * FROM vehiculos WHERE id = ?", [$id]);

        if (count($rows) === 0) {
            throw new Exception("Vehículo no encontrado", 2);
        }

        return VehiculoMappers::to_entity($rows[0]);
    }

    public function save(Vehiculo $vehiculo): int
    {
        $sql = "INSERT INTO vehiculos (marca, modelo, anio, categoria, estado)
                VALUES (?, ?, ?, ?, ?)";

        $this->db->query($sql, [
            $vehiculo->get_marca(),
            $vehiculo->get_modelo(),
            $vehiculo->get_anio(),
            $vehiculo->get_categoria(),
            $vehiculo->get_estado()
        ]);

        return $this->db->last_insert_id();
    }

    public function update(Vehiculo $vehiculo): bool
    {
        $sql = "UPDATE vehiculos
                SET marca = ?, modelo = ?, anio = ?, categoria = ?, estado = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $vehiculo->get_marca(),
            $vehiculo->get_modelo(),
            $vehiculo->get_anio(),
            $vehiculo->get_categoria(),
            $vehiculo->get_estado(),
            $vehiculo->get_id()
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->db->query("DELETE FROM vehiculos WHERE id = ?", [$id]);
    }

    public function update_estado(int $id, string $estado): bool
    {
        return $this->db->query(
            "UPDATE vehiculos SET estado = ? WHERE id = ?",
            [$estado, $id]
        );
    }

    public function tiene_reservas(int $id): bool
    {
        $rows = $this->db->query_select(
            "SELECT COUNT(*) AS total FROM reservas WHERE vehiculo_id = ?",
            [$id]
        );

        return (int) ($rows[0]["total"] ?? 0) > 0;
    }
}
