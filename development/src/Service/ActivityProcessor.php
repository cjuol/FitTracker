<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\ActivityType;
use App\Model\StrengthSet;
use App\Model\CardioSet;
use App\Model\ActivitySetInterface;

/**
 * El cerebro de la aplicación. 
 * Transforma el contenido de texto en objetos de dominio según el tipo de actividad.
 */
final class ActivityProcessor
{
    /**
     * Procesa sets estructurados enviados por el formulario.
     * @param array<int, array<string, mixed>> $sets
     * @return ActivitySetInterface[]
     */
    public function processStructured(ActivityType $type, array $sets): array
    {
        $processedData = [];

        foreach ($sets as $set) {
            if (!\is_array($set)) {
                continue;
            }

            $processedData[] = match ($type) {
                ActivityType::STRENGTH => $this->createStrengthSetFromArray($set),
                ActivityType::CARDIO   => $this->createCardioSetFromArray($set),
                ActivityType::HYBRID   => $this->createStrengthSetFromArray($set),
            };
        }

        return $processedData;
    }

    /**
     * Procesa el contenido y devuelve un array de objetos (Fuerza o Cardio).
     * * @return ActivitySetInterface[]
     * @throws \InvalidArgumentException Si el formato de línea no es válido para el tipo.
     */
    public function process(ActivityType $type, string $content): array
    {
        // Limpiamos el texto y lo dividimos por líneas
        $lines = array_filter(explode("\n", trim($content)));
        $processedData = [];

        foreach ($lines as $line) {
            // str_getcsv maneja mejor los espacios tras las comas que explode()
            $parts = str_getcsv($line);
            
            // Ignoramos líneas vacías o incompletas (mínimo 4 campos para ambos tipos)
            if (count($parts) < 4) {
                continue;
            }

            // Aquí es donde el Enum decide qué objeto instanciar
            $processedData[] = match ($type) {
                ActivityType::STRENGTH => $this->createStrengthSet($parts),
                ActivityType::CARDIO   => $this->createCardioSet($parts),
                ActivityType::HYBRID   => $this->createStrengthSet($parts), // Por ahora tratamos híbrido como fuerza
            };
        }

        return $processedData;
    }

    /**
     * Mapea los datos a un objeto de Fuerza.
     * Estructura esperada: Ejercicio, Reps, RIR, Peso, Nota(opcional)
     */
    private function createStrengthSet(array $parts): StrengthSet
    {
        return new StrengthSet(
            exercise: trim($parts[0]),
            reps:     (int) $parts[1],
            rir:      (int) $parts[2],
            weight:   (float) $parts[3],
            note:     $parts[4] ?? null
        );
    }

    /**
     * @param array<string, mixed> $set
     */
    private function createStrengthSetFromArray(array $set): StrengthSet
    {
        return new StrengthSet(
            exercise: (string) ($set['exercise'] ?? ''),
            reps:     (int) ($set['reps'] ?? 0),
            rir:      (int) ($set['rir'] ?? 0),
            weight:   (float) ($set['weight'] ?? 0),
            note:     $set['note'] ?? null
        );
    }

    /**
     * Mapea los datos a un objeto de Cardio.
     * Estructura esperada: Actividad, Distancia(km), Tiempo(min), FC_Media(opcional), Nota(opcional)
     */
    private function createCardioSet(array $parts): CardioSet
    {
        return new CardioSet(
            activityName:    trim($parts[0]),
            distanceKm:      (float) $parts[1],
            durationMinutes: (int) $parts[2],
            avgHeartRate:    isset($parts[3]) && $parts[3] !== '' ? (int) $parts[3] : null,
            note:            $parts[4] ?? null
        );
    }

    /**
     * @param array<string, mixed> $set
     */
    private function createCardioSetFromArray(array $set): CardioSet
    {
        return new CardioSet(
            activityName:    (string) ($set['activityName'] ?? $set['activity'] ?? ''),
            distanceKm:      (float) ($set['distanceKm'] ?? $set['distance'] ?? 0),
            durationMinutes: (int) ($set['durationMinutes'] ?? $set['duration'] ?? 0),
            avgHeartRate:    isset($set['avgHeartRate']) ? (int) $set['avgHeartRate'] : (isset($set['avg_hr']) ? (int) $set['avg_hr'] : null),
            note:            $set['note'] ?? null
        );
    }
}