<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Contrato para todos los tipos de sets de entrenamiento.
 * Garantiza que el procesador y la entidad puedan manejar los datos de forma genérica.
 */
interface ActivitySetInterface
{
    /**
     * Transforma el objeto en un array asociativo para su almacenamiento en JSON.
     */
    public function toArray(): array;
}