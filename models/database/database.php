<?php

namespace app\models\database;

use Exception;
use mysqli;
use mysqli_stmt;

class Database implements DatabaseInterface
{
    private string $host_db = "localhost";
    private string $user_db = "root";
    private string $pwd_db = "";
    private string $name_db = "alquiler_vehiculos_db";
    private int $port_db = 3306;

    private mysqli $conex;
    private int $last_id = 0;

    public function __construct()
    {
        $this->conex = new mysqli(
            $this->host_db,
            $this->user_db,
            $this->pwd_db,
            $this->name_db,
            $this->port_db
        );

        if ($this->conex->connect_error) {
            throw new Exception("Error de conexión: " . $this->conex->connect_error);
        }

        $this->conex->set_charset("utf8mb4");
    }

    public function query_select(string $sql, array $params = []): array
    {
        $stmt = $this->prepare($sql, $params);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result === false ? [] : $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    public function query(string $sql, array $params = []): bool
    {
        $stmt = $this->prepare($sql, $params);
        $ok = $stmt->execute();

        if ($stmt->insert_id > 0) {
            $this->last_id = $stmt->insert_id;
        }

        $stmt->close();

        return $ok;
    }

    public function last_insert_id(): int
    {
        return $this->last_id;
    }

    public function begin_transaction(): void
    {
        $this->conex->begin_transaction();
    }

    public function commit(): void
    {
        $this->conex->commit();
    }

    public function rollback(): void
    {
        $this->conex->rollback();
    }

    private function prepare(string $sql, array $params): mysqli_stmt
    {
        $stmt = $this->conex->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error al preparar la consulta: " . $this->conex->error);
        }

        if (count($params) === 0) {
            return $stmt;
        }

        $types = "";
        $values = [];

        foreach ($params as $value) {
            if (is_int($value)) {
                $types .= "i";
            } elseif (is_float($value)) {
                $types .= "d";
            } else {
                $types .= "s";
            }

            $values[] = $value;
        }

        $references = [$types];

        foreach ($values as $index => $value) {
            $references[] = &$values[$index];
        }

        call_user_func_array([$stmt, "bind_param"], $references);

        return $stmt;
    }
}
