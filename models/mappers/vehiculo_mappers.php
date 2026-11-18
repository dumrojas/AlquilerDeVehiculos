<?php

namespace app\models\mappers;

use app\models\entities\Vehiculo;

class VehiculoMappers
{
    public static function to_entity(array $row_db): Vehiculo
    {
        return new Vehiculo(
            (int) $row_db["id"],
            $row_db["marca"],
            $row_db["modelo"],
            (int) $row_db["anio"],
            $row_db["categoria"] ?? "",
            $row_db["estado"] ?? "disponible"
        );
    }
}
