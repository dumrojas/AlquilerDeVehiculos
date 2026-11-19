<?php

namespace app\controllers\reservas;

use app\controllers\exceptions\DatosInvalidos;
use app\controllers\mappers\ReservasMapperController;
use app\models\entities\Reserva;
use app\models\repositories\reservas_repository\ReservasRepositoryInterface;
use app\models\repositories\vehiculos_repository\VehiculosRepositoryInterface;
use DateTime;

class ReservasController implements ReservasControllerInterface
{
    private ReservasRepositoryInterface $reservas_model;
    private VehiculosRepositoryInterface $vehiculos_model;

    public function __construct(
        ReservasRepositoryInterface $reservas_model,
        VehiculosRepositoryInterface $vehiculos_model
    ) {
        $this->reservas_model = $reservas_model;
        $this->vehiculos_model = $vehiculos_model;
    }

    public function get_reservas(): array
    {
        return $this->reservas_model->get_reservas();
    }

    public function get_reservas_activas(): array
    {
        return $this->reservas_model->get_reservas_activas();
    }

    public function get_reserva(int $id): Reserva
    {
        return $this->reservas_model->find_by_id($id);
    }

    public function save_reserva(array $request): bool
    {
        $reserva = new Reserva(
            0,
            (int) ($request["cliente_id"] ?? 0),
            (int) ($request["vehiculo_id"] ?? 0),
            (string) ($request["fecha_inicio"] ?? ""),
            (string) ($request["fecha_fin"] ?? ""),
            "activa"
        );

        $this->validar($reserva, 0);

        $ok = $this->reservas_model->save($reserva);
        $this->sincronizar_estado_vehiculo($reserva->get_vehiculo_id());

        return $ok;
    }

    public function update_reserva(array $request): bool
    {
        $reserva = ReservasMapperController::to_entity($request);

        if ($reserva->get_id() <= 0) {
            throw new DatosInvalidos("La reserva que intenta modificar no es válida.");
        }

        $anterior = $this->reservas_model->find_by_id($reserva->get_id());
        $this->validar($reserva, $reserva->get_id());

        $ok = $this->reservas_model->update($reserva);
        $this->sincronizar_estado_vehiculo($anterior->get_vehiculo_id());
        $this->sincronizar_estado_vehiculo($reserva->get_vehiculo_id());

        return $ok;
    }

    public function cancelar_reserva(int $id): bool
    {
        $reserva = $this->reservas_model->find_by_id($id);

        if ($reserva->get_estado() !== "activa") {
            throw new DatosInvalidos("Solo se pueden cancelar reservas activas.");
        }

        $ok = $this->reservas_model->update_estado($id, "cancelada");
        $this->sincronizar_estado_vehiculo($reserva->get_vehiculo_id());

        return $ok;
    }

    public function devolver_reserva(int $id): bool
    {
        $reserva = $this->reservas_model->find_by_id($id);

        if ($reserva->get_estado() !== "activa") {
            throw new DatosInvalidos("Solo se pueden devolver reservas activas.");
        }

        $ok = $this->reservas_model->update_estado($id, "completada");
        $this->sincronizar_estado_vehiculo($reserva->get_vehiculo_id());

        return $ok;
    }

    public function delete_reserva(int $id): bool
    {
        $reserva = $this->reservas_model->find_by_id($id);

        $ok = $this->reservas_model->delete($id);
        $this->sincronizar_estado_vehiculo($reserva->get_vehiculo_id());

        return $ok;
    }

    public function historial_por_vehiculo(int $vehiculo_id): array
    {
        return $this->reservas_model->historial_por_vehiculo($vehiculo_id);
    }

    public function historial_por_cliente(int $cliente_id): array
    {
        return $this->reservas_model->historial_por_cliente($cliente_id);
    }

    private function validar(Reserva $reserva, int $excluir_id): void
    {
        if ($reserva->get_cliente_id() <= 0) {
            throw new DatosInvalidos("Debe seleccionar un cliente.");
        }

        if ($reserva->get_vehiculo_id() <= 0) {
            throw new DatosInvalidos("Debe seleccionar un vehículo.");
        }

        $inicio = DateTime::createFromFormat("Y-m-d", $reserva->get_fecha_inicio());
        $fin = DateTime::createFromFormat("Y-m-d", $reserva->get_fecha_fin());

        if ($inicio === false || $fin === false) {
            throw new DatosInvalidos("Las fechas de la reserva no son válidas.");
        }

        if ($fin < $inicio) {
            throw new DatosInvalidos("La fecha de fin no puede ser anterior a la fecha de inicio.");
        }

        $vehiculo = $this->vehiculos_model->find_by_id($reserva->get_vehiculo_id());

        if ($vehiculo->get_estado() === "mantenimiento") {
            throw new DatosInvalidos("El vehículo está en mantenimiento y no se puede reservar.");
        }

        $solapada = $this->reservas_model->hay_solapamiento(
            $reserva->get_vehiculo_id(),
            $reserva->get_fecha_inicio(),
            $reserva->get_fecha_fin(),
            $excluir_id
        );

        if ($solapada) {
            throw new DatosInvalidos("El vehículo ya tiene una reserva activa en ese rango de fechas.");
        }
    }

    private function sincronizar_estado_vehiculo(int $vehiculo_id): void
    {
        $vehiculo = $this->vehiculos_model->find_by_id($vehiculo_id);

        if ($vehiculo->get_estado() === "mantenimiento") {
            return;
        }

        $hoy = date("Y-m-d");
        $vigente = $this->reservas_model->hay_solapamiento($vehiculo_id, $hoy, $hoy);

        $this->vehiculos_model->update_estado($vehiculo_id, $vigente ? "alquilado" : "disponible");
    }
}
