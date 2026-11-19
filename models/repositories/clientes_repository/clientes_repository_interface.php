<?php

namespace app\models\repositories\clientes_repository;

use app\models\entities\Cliente;

interface ClientesRepositoryInterface
{
    public function get_clientes(): array;

    public function find_by_id(int $id): Cliente;

    public function save(Cliente $cliente): bool;

    public function update(Cliente $cliente): bool;

    public function delete(int $id): bool;

    public function tiene_reservas(int $id): bool;
}
