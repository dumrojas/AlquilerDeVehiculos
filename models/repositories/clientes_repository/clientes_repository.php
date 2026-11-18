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
        $rows = $this->db->select("SELECT * FROM clientes ORDER BY nombre");

        return array_map(
            fn(array $row): Cliente => ClienteMappers::to_entity($row),
            $rows
        );
    }

    public function find_by_id(int $id): Cliente
    {
        $rows = $this->db->select("SELECT * FROM clientes WHERE id = ?", [$id]);

        if (count($rows) === 0) {
            throw new Exception("Cliente no encontrado", 2);
        }

        return ClienteMappers::to_entity($rows[0]);
    }

    public function save(Cliente $cliente): int
    {
        $sql = "INSERT INTO clientes (nombre, telefono, correo, numero_licencia)
                VALUES (?, ?, ?, ?)";

        $this->db->execute($sql, [
            $cliente->get_nombre(),
            $cliente->get_telefono(),
            $cliente->get_correo(),
            $cliente->get_numero_licencia()
        ]);

        return $this->db->last_insert_id();
    }

    public function update(Cliente $cliente): bool
    {
        $sql = "UPDATE clientes
                SET nombre = ?, telefono = ?, correo = ?, numero_licencia = ?
                WHERE id = ?";

        return $this->db->execute($sql, [
            $cliente->get_nombre(),
            $cliente->get_telefono(),
            $cliente->get_correo(),
            $cliente->get_numero_licencia(),
            $cliente->get_id()
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM clientes WHERE id = ?", [$id]);
    }

    public function tiene_reservas(int $id): bool
    {
        $rows = $this->db->select(
            "SELECT COUNT(*) AS total FROM reservas WHERE cliente_id = ?",
            [$id]
        );

        return (int) ($rows[0]["total"] ?? 0) > 0;
    }
}
