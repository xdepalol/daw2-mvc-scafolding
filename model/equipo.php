<?php

namespace App\Model;

use DateTime;

class Equipo
{
    // --- Propietats ---
    private ?int $id;        // pot ser null si encara no està guardat
    private string $nombre;
    private string $ciudad;
    private string $pais;
    private ?\DateTimeImmutable $created_at;
    private ?\DateTimeImmutable $updated_at;

    // --- Constructor ---
    public function __construct(?int $id = null, string $nombre = '', string $ciudad = '', string $pais = '', ?\DateTimeImmutable $created_at = null, ?\DateTimeImmutable $updated_at = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->ciudad = $ciudad;
        $this->pais = $pais;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // --- Getters ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getCiudad(): string
    {
        return $this->ciudad;
    }

    public function getPais(): string
    {
        return $this->pais;
    }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->created_at; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updated_at; }

    // --- Setters ---
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setCiudad(string $ciudad): void
    {
        $this->ciudad = $ciudad;
    }

    public function setPais(string $pais): void
    {
        $this->pais = $pais;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): void { $this->created_at = $created_at; }
    public function setUpdatedAt(?\DateTimeImmutable $updated_at): void { $this->updated_at = $updated_at; }

    // --- Utilitats opcionals ---
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'ciudad' => $this->ciudad,
            'pais' => $this->pais,
            'create_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
