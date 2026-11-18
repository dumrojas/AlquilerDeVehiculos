<?php

namespace app\controllers\clientes;

use app\controllers\exceptions\DatosInvalidos;
use app\controllers\mappers\ClientesMapperController;
use app\models\entities\Cliente;
use app\models\repositories\clientes_repository\ClientesRepositoryInterface;

class ClientesController implements ClientesControllerInterface
{
    private ClientesRepositoryInterface $clientes_model;

    public function __construct(ClientesRepositoryInterface $clientes_model)
    {
        $this->clientes_model = $clientes_model;
    }

    public function get_clientes(): array
    {
        return $this->clientes_model->get_clientes();
    }

    public function get_cliente(int $id): Cliente
    {
        return $this->clientes_model->find_by_id($id);
    }

    public function save_cliente(array $request): int
    {
        $cliente = ClientesMapperController::to_entity($request);
        $this->validar($cliente);

        return $this->clientes_model->save($cliente);
    }

    public function update_cliente(array $request): bool
    {
        $cliente = ClientesMapperController::to_entity($request);
        $this->validar($cliente);

        if ($cliente->get_id() <= 0) {
            throw new DatosInvalidos("El cliente que intenta modificar no es válido.");
        }

        return $this->clientes_model->update($cliente);
    }

    public function delete_cliente(int $id): bool
    {
        if ($this->clientes_model->tiene_reservas($id)) {
            throw new DatosInvalidos("No se puede eliminar el cliente porque tiene reservas registradas.");
        }

        return $this->clientes_model->delete($id);
    }

    private function validar(Cliente $cliente): void
    {
        if ($cliente->get_nombre() === "") {
            throw new DatosInvalidos("El nombre del cliente es obligatorio.");
        }

        if ($cliente->get_correo() !== "" && !filter_var($cliente->get_correo(), FILTER_VALIDATE_EMAIL)) {
            throw new DatosInvalidos("El correo del cliente no tiene un formato válido.");
        }

        if ($cliente->get_telefono() !== "" && !preg_match("/^[0-9+()\\-\\s]{6,20}$/", $cliente->get_telefono())) {
            throw new DatosInvalidos("El teléfono del cliente no tiene un formato válido.");
        }
    }
}
