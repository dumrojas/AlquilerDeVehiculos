<?php

class DB
{
    private const HOST = "localhost";
    private const USER = "root";
    private const PASSWORD = "";
    private const NAME = "alquiler_vehiculos_db";
    private const PORT = 3306;

    public static function conectar(): mysqli
    {
        $conexion = new mysqli(
            self::HOST,
            self::USER,
            self::PASSWORD,
            self::NAME,
            self::PORT
        );

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $conexion->set_charset("utf8mb4");

        return $conexion;
    }
}
