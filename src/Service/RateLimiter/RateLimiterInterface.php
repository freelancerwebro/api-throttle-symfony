<?php

declare(strict_types=1);

namespace App\Service\RateLimiter;

use App\Entity\Client;

interface RateLimiterInterface {
    public function allowRequest(Client $client, string $endpoint): bool;
}
