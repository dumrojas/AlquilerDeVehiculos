<?php

namespace app\controllers\reservas;

use app\models\entities\Reserva;

interface ReservasControllerInterface
{
    public function get_reservas(): array;

    public function get_reservas_activas(): array;

    public function get_reserva(int $id): Reserva;

    public function save_reserva(array $request): bool;

    public function update_reserva(array $request): bool;

    public function cancelar_reserva(int $id): bool;

    public function devolver_reserva(int $id): bool;

    public function delete_reserva(int $id): bool;

    public function historial_por_vehiculo(int $vehiculo_id): array;

    public function historial_por_cliente(int $cliente_id): array;
}
