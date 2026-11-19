<?php

namespace app\models\database;

interface DatabaseInterface
{
    public function query_select(string $sql): array;

    public function query(string $sql): bool;
}
