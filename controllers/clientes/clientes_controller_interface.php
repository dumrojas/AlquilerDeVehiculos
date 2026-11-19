<?php

namespace app\controllers\clientes;

use app\models\entities\Cliente;

interface ClientesControllerInterface
{
    public function get_clientes(): array;

    public function get_cliente(int $id): Cliente;

    public function save_cliente(array $request): bool;

    public function update_cliente(array $request): bool;

    public function delete_cliente(int $id): bool;
}
