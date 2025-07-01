<?php

declare(strict_types=1);

namespace App\Service\Storage;

readonly class StorageFactory
{
    public function __construct(
        private MemoryStorage $memory,
        private SqliteStorage $sqlite,
    ) {}

    public function get(string $strategy): RateLimitStorageInterface
    {
        return match ($strategy) {
            'memory' => $this->memory,
            'sqlite' => $this->sqlite,
            default => throw new \InvalidArgumentException("Unsupported storage: $strategy"),
        };
    }
}
