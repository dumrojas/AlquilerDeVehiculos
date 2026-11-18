<?php

namespace app\models\mappers;

use app\models\entities\Cliente;

class ClienteMappers
{
    public static function to_entity(array $row_db): Cliente
    {
        return new Cliente(
            (int) $row_db["id"],
            $row_db["nombre"],
            $row_db["telefono"] ?? "",
            $row_db["correo"] ?? "",
            $row_db["numero_licencia"] ?? ""
        );
    }
}
