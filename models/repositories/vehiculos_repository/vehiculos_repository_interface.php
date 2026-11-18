<?php

namespace app\models\repositories\vehiculos_repository;

use app\models\entities\Vehiculo;

interface VehiculosRepositoryInterface
{
    public function get_vehiculos(): array;

    public function get_vehiculos_disponibles(): array;

    public function find_by_id(int $id): Vehiculo;

    public function save(Vehiculo $vehiculo): int;

    public function update(Vehiculo $vehiculo): bool;

    public function delete(int $id): bool;

    public function update_estado(int $id, string $estado): bool;

    public function tiene_reservas(int $id): bool;
}
