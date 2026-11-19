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
        $sql = "select * from vehiculos order by marca, modelo";
        $rows = $this->db->query_select($sql);

        return array_map(
            fn(array $row): Vehiculo => VehiculoMappers::to_entity($row),
            $rows
        );
    }

    public function get_vehiculos_disponibles(): array
    {
        $sql = "select v.* from vehiculos v
                where v.estado = 'disponible'
                and not exists (
                    select 1 from reservas r
                    where r.vehiculo_id = v.id
                    and r.estado = 'activa'
                    and curdate() between r.fecha_inicio and r.fecha_fin
                )
                order by v.marca, v.modelo";

        $rows = $this->db->query_select($sql);

        return array_map(
            fn(array $row): Vehiculo => VehiculoMappers::to_entity($row),
            $rows
        );
    }

    public function find_by_id(int $id): Vehiculo
    {
        $sql = "select * from vehiculos where id=$id";
        $rows = $this->db->query_select($sql);

        if (count($rows) === 0) {
            throw new Exception("Vehículo no encontrado", 2);
        }

        return VehiculoMappers::to_entity($rows[0]);
    }

    public function save(Vehiculo $vehiculo): bool
    {
        $sql = "insert into vehiculos (marca, modelo, anio, categoria, estado) values";
        $sql .= "('" . $vehiculo->get_marca() . "','" . $vehiculo->get_modelo() . "',";
        $sql .= $vehiculo->get_anio() . ",'" . $vehiculo->get_categoria() . "',";
        $sql .= "'" . $vehiculo->get_estado() . "')";

        return $this->db->query($sql);
    }

    public function update(Vehiculo $vehiculo): bool
    {
        $sql = "update vehiculos set ";
        $sql .= "marca='" . $vehiculo->get_marca() . "', ";
        $sql .= "modelo='" . $vehiculo->get_modelo() . "', ";
        $sql .= "anio=" . $vehiculo->get_anio() . ", ";
        $sql .= "categoria='" . $vehiculo->get_categoria() . "', ";
        $sql .= "estado='" . $vehiculo->get_estado() . "' ";
        $sql .= "where id=" . $vehiculo->get_id();

        return $this->db->query($sql);
    }

    public function delete(int $id): bool
    {
        $sql = "delete from vehiculos where id=$id";

        return $this->db->query($sql);
    }

    public function update_estado(int $id, string $estado): bool
    {
        $sql = "update vehiculos set estado='$estado' where id=$id";

        return $this->db->query($sql);
    }

    public function tiene_reservas(int $id): bool
    {
        $sql = "select count(*) as total from reservas where vehiculo_id=$id";
        $rows = $this->db->query_select($sql);

        return (int) ($rows[0]["total"] ?? 0) > 0;
    }
}
