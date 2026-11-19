<?php

namespace app\controllers\config;

use app\controllers\vehiculos\VehiculosController;
use app\controllers\vehiculos\VehiculosControllerInterface;
use app\controllers\clientes\ClientesController;
use app\controllers\clientes\ClientesControllerInterface;
use app\controllers\reservas\ReservasController;
use app\controllers\reservas\ReservasControllerInterface;
use app\models\config\ModelsConfig;

class ControllersConfig
{
    public static function get_vehiculos_controller(): VehiculosControllerInterface
    {
        return new VehiculosController(ModelsConfig::get_vehiculos_repository());
    }

    public static function get_clientes_controller(): ClientesControllerInterface
    {
        return new ClientesController(ModelsConfig::get_clientes_repository());
    }

    public static function get_reservas_controller(): ReservasControllerInterface
    {
        return new ReservasController(
            ModelsConfig::get_reservas_repository(),
            ModelsConfig::get_vehiculos_repository()
        );
    }
}
