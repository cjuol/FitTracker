<?php

declare(strict_types=1);
namespace App\Model;

final readonly class CardioSet implements ActivitySetInterface
{
    public function __construct(
        public string $activityName,
        public float $distanceKm,
        public int $durationMinutes,
        public ?int $avgHeartRate = null,
        public ?string $note = null
    ) {}

    public function toArray(): array
    {
        return [
            'activity' => $this->activityName,
            'distance' => $this->distanceKm,
            'duration' => $this->durationMinutes,
            'avg_hr'   => $this->avgHeartRate,
            'note'     => $this->note,
        ];
    }
}