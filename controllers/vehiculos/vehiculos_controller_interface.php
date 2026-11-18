<?php

namespace app\controllers\vehiculos;

use app\models\entities\Vehiculo;

interface VehiculosControllerInterface
{
    public function get_vehiculos(): array;

    public function get_vehiculos_disponibles(): array;

    public function get_vehiculo(int $id): Vehiculo;

    public function save_vehiculo(array $request): int;

    public function update_vehiculo(array $request): bool;

    public function delete_vehiculo(int $id): bool;
}
