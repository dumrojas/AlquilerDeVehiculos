<?php

namespace app\controllers\vehiculos;

use app\controllers\exceptions\DatosInvalidos;
use app\controllers\mappers\VehiculosMapperController;
use app\models\entities\Vehiculo;
use app\models\repositories\vehiculos_repository\VehiculosRepositoryInterface;

class VehiculosController implements VehiculosControllerInterface
{
    private const ESTADOS = ["disponible", "alquilado", "mantenimiento"];

    private VehiculosRepositoryInterface $vehiculos_model;

    public function __construct(VehiculosRepositoryInterface $vehiculos_model)
    {
        $this->vehiculos_model = $vehiculos_model;
    }

    public function get_vehiculos(): array
    {
        return $this->vehiculos_model->get_vehiculos();
    }

    public function get_vehiculos_disponibles(): array
    {
        return $this->vehiculos_model->get_vehiculos_disponibles();
    }

    public function get_vehiculo(int $id): Vehiculo
    {
        return $this->vehiculos_model->find_by_id($id);
    }

    public function save_vehiculo(array $request): int
    {
        $vehiculo = VehiculosMapperController::to_entity($request);
        $this->validar($vehiculo);

        return $this->vehiculos_model->save($vehiculo);
    }

    public function update_vehiculo(array $request): bool
    {
        $vehiculo = VehiculosMapperController::to_entity($request);
        $this->validar($vehiculo);

        if ($vehiculo->get_id() <= 0) {
            throw new DatosInvalidos("El vehículo que intenta modificar no es válido.");
        }

        return $this->vehiculos_model->update($vehiculo);
    }

    public function delete_vehiculo(int $id): bool
    {
        if ($this->vehiculos_model->tiene_reservas($id)) {
            throw new DatosInvalidos("No se puede eliminar el vehículo porque tiene reservas registradas.");
        }

        return $this->vehiculos_model->delete($id);
    }

    private function validar(Vehiculo $vehiculo): void
    {
        if ($vehiculo->get_marca() === "") {
            throw new DatosInvalidos("La marca del vehículo es obligatoria.");
        }

        if ($vehiculo->get_modelo() === "") {
            throw new DatosInvalidos("El modelo del vehículo es obligatorio.");
        }

        $anio_maximo = (int) date("Y") + 1;

        if ($vehiculo->get_anio() < 1900 || $vehiculo->get_anio() > $anio_maximo) {
            throw new DatosInvalidos("El año del vehículo debe estar entre 1900 y " . $anio_maximo . ".");
        }

        if (!in_array($vehiculo->get_estado(), self::ESTADOS, true)) {
            throw new DatosInvalidos("El estado del vehículo no es válido.");
        }
    }
}
