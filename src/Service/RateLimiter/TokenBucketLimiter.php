<?php

declare(strict_types=1);

namespace App\Service\RateLimiter;

use App\Entity\Client;
use App\Service\Storage\RateLimitStorageInterface;

readonly class TokenBucketLimiter implements RateLimiterInterface
{
    public function __construct(
        private RateLimitStorageInterface $storage
    ) {
    }

    public function allowRequest(Client $client, string $endpoint): bool
    {
        $clientId = $client->getId();
        $key = "rate_limit:bucket:{$clientId}:{$endpoint}";
        $bucket = $this->storage->get($key);

        $now = time();

        $config = $client->getRateLimitForEndpoint($endpoint);

        $capacity = $config->getMaxLimit();
        $refillInterval = $config->getInterval(); // in seconds
        $refillRate = $capacity / $refillInterval; // tokens per second

        if (!$bucket) {
            $bucket = [
                'tokens' => $capacity - 1,
                'last_refill' => $now
            ];
            $this->storage->set($key, $bucket, $refillInterval);
            return true;
        }

        $elapsed = $now - $bucket['last_refill'];
        $refilledTokens = $elapsed * $refillRate;
        $bucket['tokens'] = min($capacity, $bucket['tokens'] + $refilledTokens);
        $bucket['last_refill'] = $now;

        if ($bucket['tokens'] < 1) {
            return false;
        }

        $bucket['tokens'] -= 1;
        $this->storage->set($key, $bucket, $refillInterval);

        return true;
    }
}
