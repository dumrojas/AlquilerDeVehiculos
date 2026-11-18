<?php

namespace app\models\database;

use mysqli;
use mysqli_stmt;
use RuntimeException;

class Database implements DatabaseInterface
{
    private mysqli $conex;
    private int $last_id = 0;

    public function __construct()
    {
        $this->conex = \DB::conectar();
    }

    public function select(string $sql, array $params = []): array
    {
        $stmt = $this->prepare($sql, $params);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result === false ? [] : $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->prepare($sql, $params);
        $ok = $stmt->execute();
        $this->last_id = $stmt->insert_id > 0 ? $stmt->insert_id : $this->last_id;
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
            throw new RuntimeException("Error al preparar la consulta: " . $this->conex->error);
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
