<?php

namespace app\controllers\mappers;

use app\models\entities\Cliente;

class ClientesMapperController
{
    public static function to_entity(array $request): Cliente
    {
        return new Cliente(
            empty($request["id"]) ? 0 : (int) $request["id"],
            trim($request["nombre"] ?? ""),
            trim($request["telefono"] ?? ""),
            trim($request["correo"] ?? ""),
            trim($request["numero_licencia"] ?? "")
        );
    }
}
