<?php

namespace app\models\config;

use app\models\database\Database;
use app\models\database\DatabaseInterface;
use app\models\repositories\vehiculos_repository\VehiculosRepository;
use app\models\repositories\vehiculos_repository\VehiculosRepositoryInterface;
use app\models\repositories\clientes_repository\ClientesRepository;
use app\models\repositories\clientes_repository\ClientesRepositoryInterface;
use app\models\repositories\reservas_repository\ReservasRepository;
use app\models\repositories\reservas_repository\ReservasRepositoryInterface;

class ModelsConfig
{
    private static ?DatabaseInterface $db = null;

    public static function get_db(): DatabaseInterface
    {
        if (self::$db === null) {
            self::$db = new Database();
        }

        return self::$db;
    }

    public static function get_vehiculos_repository(): VehiculosRepositoryInterface
    {
        return new VehiculosRepository(self::get_db());
    }

    public static function get_clientes_repository(): ClientesRepositoryInterface
    {
        return new ClientesRepository(self::get_db());
    }

    public static function get_reservas_repository(): ReservasRepositoryInterface
    {
        return new ReservasRepository(self::get_db());
    }
}
