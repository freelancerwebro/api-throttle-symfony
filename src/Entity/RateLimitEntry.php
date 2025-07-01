<?php

namespace App\Entity;

use App\Repository\RateLimitEntryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RateLimitEntryRepository::class)]
class RateLimitEntry
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $fieldKey = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fieldValue = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFieldKey(): ?string
    {
        return $this->fieldKey;
    }

    public function setFieldKey(string $fieldKey): static
    {
        $this->fieldKey = $fieldKey;

        return $this;
    }

    public function getFieldValue(): ?string
    {
        return $this->fieldValue;
    }

    public function setFieldValue(?string $fieldValue): static
    {
        $this->fieldValue = $fieldValue;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}
