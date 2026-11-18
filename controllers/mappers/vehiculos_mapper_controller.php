<?php

namespace app\controllers\mappers;

use app\models\entities\Vehiculo;

class VehiculosMapperController
{
    public static function to_entity(array $request): Vehiculo
    {
        return new Vehiculo(
            empty($request["id"]) ? 0 : (int) $request["id"],
            trim($request["marca"] ?? ""),
            trim($request["modelo"] ?? ""),
            empty($request["anio"]) ? 0 : (int) $request["anio"],
            trim($request["categoria"] ?? ""),
            $request["estado"] ?? "disponible"
        );
    }
}
