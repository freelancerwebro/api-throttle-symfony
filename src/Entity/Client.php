<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: ClientRateLimit::class, mappedBy: 'client', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $rateLimits;

    public function __construct()
    {
        $this->rateLimits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRateLimitForEndpoint(string $endpoint): ?ClientRateLimit
    {
        foreach ($this->rateLimits as $limit) {
            if ($limit->getEndpoint() === $endpoint) {
                return $limit;
            }
        }

        return null;
    }

    public function getMaxLimitFor(string $endpoint): ?int
    {
        $limit = $this->getRateLimitForEndpoint($endpoint);
        return $limit?->getMaxLimit();
    }

    public function getIntervalFor(string $endpoint): ?int
    {
        $limit = $this->getRateLimitForEndpoint($endpoint);
        return $limit?->getInterval();
    }
}
