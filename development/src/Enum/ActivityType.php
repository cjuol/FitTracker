<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Define los tipos de actividad permitidos en el sistema.
 * Documentado: Garantiza integridad en la base de datos y lógica de negocio.
 */
enum ActivityType: string
{
    case STRENGTH = 'strength';
    case CARDIO   = 'cardio';
    case HYBRID   = 'hybrid';

    /**
     * Retorna una etiqueta legible para el frontend.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::STRENGTH => 'Entrenamiento de Fuerza',
            self::CARDIO   => 'Actividad Cardiovascular',
            self::HYBRID   => 'Entrenamiento Híbrido / Hyrox',
        };
    }
}