<?php

declare(strict_types=1);

namespace App\Service\Storage;

interface RateLimitStorageInterface
{
    public function get(string $key): mixed;
    public function set(string $key, mixed $value, int $ttlInSeconds = null): void;
    public function delete(string $key): void;
}
