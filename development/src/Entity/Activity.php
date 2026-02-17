<?php

namespace App\Entity;

use App\Enum\ActivityType;
use App\Repository\ActivityRepository;
use App\Model\ActivitySetInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $rawContent = null;

    #[ORM\Column]
    private array $data = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(enumType: ActivityType::class)]
    private ?ActivityType $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRawContent(): ?string
    {
        return $this->rawContent;
    }

    public function setRawContent(string $rawContent): static
    {
        $this->rawContent = $rawContent;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getType(): ?ActivityType
    {
        return $this->type;
    }

    public function setType(ActivityType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Hidrata el campo data a partir de una colección de objetos que implementan ActivitySetInterface.
     * * @param ActivitySetInterface[] $sets
     */
    public function setPayloadFromSets(array $sets): self
    {
        $this->data = array_map(fn(ActivitySetInterface $set) => $set->toArray(), $sets);

        return $this;
    }
}
