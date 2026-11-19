<?php

namespace app\models\database;

interface DatabaseInterface
{
    public function query_select(string $sql, array $params = []): array;

    public function query(string $sql, array $params = []): bool;

    public function last_insert_id(): int;

    public function begin_transaction(): void;

    public function commit(): void;

    public function rollback(): void;
}
