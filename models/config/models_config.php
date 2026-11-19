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
    public static function get_db(): DatabaseInterface
    {
        return new Database();
    }

    public static function get_vehiculos_repository(): VehiculosRepositoryInterface
    {
        return new VehiculosRepository(ModelsConfig::get_db());
    }

    public static function get_clientes_repository(): ClientesRepositoryInterface
    {
        return new ClientesRepository(ModelsConfig::get_db());
    }

    public static function get_reservas_repository(): ReservasRepositoryInterface
    {
        return new ReservasRepository(ModelsConfig::get_db());
    }
}
