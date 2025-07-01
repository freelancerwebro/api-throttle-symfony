<?php

namespace App\Controller;

use App\Service\RateLimiter\TokenBucketLimiter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class FooController extends AbstractController
{
    public function __construct(
        private readonly TokenBucketLimiter $limiter
    ) {
    }

    #[Route('/foo', name: 'app_foo')]
    public function index(Request $request): JsonResponse
    {
        $client = $request->attributes->get('client');

        if (!$this->limiter->allowRequest(client: $client, endpoint: 'foo')) {
            return $this->json(['error' => 'rate limit exceeded'], 429);
        }

        return $this->json(['success' => true]);
    }
}
