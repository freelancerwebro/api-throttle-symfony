<?php

namespace App\Controller;

use App\Service\RateLimiter\FixedWindowLimiter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class BarController extends AbstractController
{
    public function __construct(
        private readonly FixedWindowLimiter $limiter
    ) {
    }

    #[Route('/bar', name: 'app_bar')]
    public function index(Request $request): JsonResponse
    {
        $client = $request->attributes->get('client');

        if (!$this->limiter->allowRequest(client: $client, endpoint: 'bar')) {
            return $this->json(['error' => 'rate limit exceeded'], 429);
        }

        return $this->json(['success' => true]);
    }
}
