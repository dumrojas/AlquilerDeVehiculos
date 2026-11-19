<?php

namespace app\models\database;

use Exception;
use mysqli;

class Database implements DatabaseInterface
{
    private string $host_db = "localhost";
    private string $user_db = "root";
    private string $pwd_db = "";
    private string $name_db = "alquiler_vehiculos_db";
    private int $port_db = 3306;
    private $conex = null;

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
            die("Error de conexión: " . $this->conex->connect_error);
        }

        $this->conex->set_charset("utf8mb4");
    }

    public function query_select(string $sql): array
    {
        $result = $this->conex->query($sql);

        if (!$result) {
            throw new Exception("Error en la consulta: " . $this->conex->error);
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            array_push($rows, $row);
        }

        return $rows;
    }

    public function query(string $sql): bool
    {
        $result = $this->conex->query($sql);

        if (!$result) {
            throw new Exception("Error en la consulta: " . $this->conex->error);
        }

        return $result;
    }
}
