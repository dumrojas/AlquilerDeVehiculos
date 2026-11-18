<?php

namespace app\models\mappers;

use app\models\entities\Reserva;

class ReservaMappers
{
    public static function to_entity(array $row_db): Reserva
    {
        return new Reserva(
            (int) $row_db["id"],
            (int) $row_db["cliente_id"],
            (int) $row_db["vehiculo_id"],
            $row_db["fecha_inicio"],
            $row_db["fecha_fin"],
            $row_db["estado"] ?? "activa",
            $row_db["cliente_nombre"] ?? null,
            $row_db["vehiculo_descripcion"] ?? null
        );
    }
}
