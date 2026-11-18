<?php

namespace app\models\entities;

class Reserva
{
    private int $id;
    private int $cliente_id;
    private int $vehiculo_id;
    private string $fecha_inicio;
    private string $fecha_fin;
    private string $estado;
    private ?string $cliente_nombre;
    private ?string $vehiculo_descripcion;

    public function __construct(
        int $id,
        int $cliente_id,
        int $vehiculo_id,
        string $fecha_inicio,
        string $fecha_fin,
        string $estado = "activa",
        ?string $cliente_nombre = null,
        ?string $vehiculo_descripcion = null
    ) {
        $this->id = $id;
        $this->cliente_id = $cliente_id;
        $this->vehiculo_id = $vehiculo_id;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->estado = $estado;
        $this->cliente_nombre = $cliente_nombre;
        $this->vehiculo_descripcion = $vehiculo_descripcion;
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function get_cliente_id(): int
    {
        return $this->cliente_id;
    }

    public function get_vehiculo_id(): int
    {
        return $this->vehiculo_id;
    }

    public function get_fecha_inicio(): string
    {
        return $this->fecha_inicio;
    }

    public function get_fecha_fin(): string
    {
        return $this->fecha_fin;
    }

    public function get_estado(): string
    {
        return $this->estado;
    }

    public function get_cliente_nombre(): string
    {
        return $this->cliente_nombre ?? "";
    }

    public function get_vehiculo_descripcion(): string
    {
        return $this->vehiculo_descripcion ?? "";
    }

    public function get_dias(): int
    {
        $inicio = new \DateTime($this->fecha_inicio);
        $fin = new \DateTime($this->fecha_fin);
        return (int) $inicio->diff($fin)->days + 1;
    }
}
