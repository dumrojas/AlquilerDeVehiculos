<?php

namespace app\controllers\mappers;

use app\models\entities\Reserva;

class ReservasMapperController
{
    public static function to_entity(array $request): Reserva
    {
        return new Reserva(
            empty($request["id"]) ? 0 : (int) $request["id"],
            (int) ($request["cliente_id"] ?? 0),
            (int) ($request["vehiculo_id"] ?? 0),
            $request["fecha_inicio"] ?? "",
            $request["fecha_fin"] ?? "",
            $request["estado"] ?? "activa"
        );
    }
}
