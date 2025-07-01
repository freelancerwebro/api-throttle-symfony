<?php

declare(strict_types=1);

namespace App\Service\Storage;

class MemoryStorage implements RateLimitStorageInterface
{
    private array $store = [];
    private array $expirations = [];

    public function get(string $key): mixed
    {
        if (isset($this->expirations[$key]) && time() > $this->expirations[$key]) {
            unset($this->store[$key], $this->expirations[$key]);
            return null;
        }

        return $this->store[$key] ?? null;
    }

    public function set(string $key, mixed $value, int $ttlInSeconds = null): void
    {
        $this->store[$key] = $value;
        if ($ttlInSeconds !== null) {
            $this->expirations[$key] = time() + $ttlInSeconds;
        }
    }

    public function delete(string $key): void
    {
        unset($this->store[$key], $this->expirations[$key]);
    }
}
