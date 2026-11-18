<?php

namespace app\models\entities;

class Cliente
{
    private int $id;
    private string $nombre;
    private string $telefono;
    private string $correo;
    private string $numero_licencia;

    public function __construct(
        int $id,
        string $nombre,
        string $telefono = "",
        string $correo = "",
        string $numero_licencia = ""
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->numero_licencia = $numero_licencia;
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public function get_nombre(): string
    {
        return $this->nombre;
    }

    public function get_telefono(): string
    {
        return $this->telefono;
    }

    public function get_correo(): string
    {
        return $this->correo;
    }

    public function get_numero_licencia(): string
    {
        return $this->numero_licencia;
    }
}
