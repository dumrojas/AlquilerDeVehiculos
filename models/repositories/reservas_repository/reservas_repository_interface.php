<?php

namespace app\models\repositories\reservas_repository;

use app\models\entities\Reserva;

interface ReservasRepositoryInterface
{
    public function get_reservas(): array;

    public function get_reservas_activas(): array;

    public function find_by_id(int $id): Reserva;

    public function save(Reserva $reserva): int;

    public function update(Reserva $reserva): bool;

    public function update_estado(int $id, string $estado): bool;

    public function delete(int $id): bool;

    public function hay_solapamiento(int $vehiculo_id, string $inicio, string $fin, int $excluir_id = 0): bool;

    public function historial_por_vehiculo(int $vehiculo_id): array;

    public function historial_por_cliente(int $cliente_id): array;

    public function contar_por_estado(string $estado): int;
}
