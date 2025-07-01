<?php

declare(strict_types=1);

namespace App\Service\RateLimiter;

use App\Entity\Client;
use App\Service\Storage\RateLimitStorageInterface;

readonly class FixedWindowLimiter implements RateLimiterInterface
{
    public function __construct(
        private RateLimitStorageInterface $storage
    ) {
    }

    public function allowRequest(Client $client, string $endpoint): bool
    {
        $clientId = $client->getId();
        $key = "rate_limit:fixed:{$clientId}:{$endpoint}";
        $current = $this->storage->get($key);

        $now = time();
        $windowStart = (int)($now / 60) * 60; // window of 60 sec

        if (!$current || $current['window'] !== $windowStart) {
            $current = ['count' => 0, 'window' => $windowStart];
        }

        $limitConfig = $client->getRateLimitForEndpoint($endpoint);
        $limit = $limitConfig->getMaxLimit();
        $interval = $limitConfig->getInterval();

        if ($current['count'] >= $limit) {
            return false;
        }

        $current['count'] += 1;
        $this->storage->set($key, $current, $interval);

        return true;
    }
}
