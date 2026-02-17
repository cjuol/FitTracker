<?php

declare(strict_types=1);

namespace App\Model;

final readonly class StrengthSet implements ActivitySetInterface
{
    public function __construct(
        public string $exercise,
        public int $reps,
        public int $rir,
        public float $weight,
        public ?string $note = null
    ) {}

    public function toArray(): array
    {
        return [
            'exercise' => $this->exercise,
            'reps'     => $this->reps,
            'rir'      => $this->rir,
            'weight'   => $this->weight,
            'note'     => $this->note,
        ];
    }
}