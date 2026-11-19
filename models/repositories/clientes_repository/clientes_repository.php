<?php

namespace app\models\repositories\clientes_repository;

use app\models\database\DatabaseInterface;
use app\models\entities\Cliente;
use app\models\mappers\ClienteMappers;
use Exception;

class ClientesRepository implements ClientesRepositoryInterface
{
    private DatabaseInterface $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function get_clientes(): array
    {
        $sql = "select * from clientes order by nombre";
        $rows = $this->db->query_select($sql);

        return array_map(
            fn(array $row): Cliente => ClienteMappers::to_entity($row),
            $rows
        );
    }

    public function find_by_id(int $id): Cliente
    {
        $sql = "select * from clientes where id=$id";
        $rows = $this->db->query_select($sql);

        if (count($rows) === 0) {
            throw new Exception("Cliente no encontrado", 2);
        }

        return ClienteMappers::to_entity($rows[0]);
    }

    public function save(Cliente $cliente): bool
    {
        $sql = "insert into clientes (nombre, telefono, correo, numero_licencia) values";
        $sql .= "('" . $cliente->get_nombre() . "','" . $cliente->get_telefono() . "',";
        $sql .= "'" . $cliente->get_correo() . "','" . $cliente->get_numero_licencia() . "')";

        return $this->db->query($sql);
    }

    public function update(Cliente $cliente): bool
    {
        $sql = "update clientes set ";
        $sql .= "nombre='" . $cliente->get_nombre() . "', ";
        $sql .= "telefono='" . $cliente->get_telefono() . "', ";
        $sql .= "correo='" . $cliente->get_correo() . "', ";
        $sql .= "numero_licencia='" . $cliente->get_numero_licencia() . "' ";
        $sql .= "where id=" . $cliente->get_id();

        return $this->db->query($sql);
    }

    public function delete(int $id): bool
    {
        $sql = "delete from clientes where id=$id";

        return $this->db->query($sql);
    }

    public function tiene_reservas(int $id): bool
    {
        $sql = "select count(*) as total from reservas where cliente_id=$id";
        $rows = $this->db->query_select($sql);

        return (int) ($rows[0]["total"] ?? 0) > 0;
    }
}
