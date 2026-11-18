<?php

namespace app\models\entities;

class Vehiculo
{
    private int $id;
    private string $marca;
    private string $modelo;
    private int $anio;
    private string $categoria;
    private string $estado;

    public function __construct(
        int $id,
        string $marca,
        string $modelo,
        int $anio,
        string $categoria = "",
        string $estado = "disponible"
    ) {
        $this->id = $id;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->anio = $anio;
        $this->categoria = $categoria;
        $this->estado = $estado;
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function get_marca(): string
    {
        return $this->marca;
    }

    public function get_modelo(): string
    {
        return $this->modelo;
    }

    public function get_anio(): int
    {
        return $this->anio;
    }

    public function get_categoria(): string
    {
        return $this->categoria;
    }

    public function get_estado(): string
    {
        return $this->estado;
    }

    public function get_descripcion(): string
    {
        return $this->marca . " " . $this->modelo . " (" . $this->anio . ")";
    }
}
