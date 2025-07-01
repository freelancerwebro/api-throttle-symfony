<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class FooController extends AbstractController
{
    #[Route('/foo', name: 'app_foo')]
    public function index(Request $request): JsonResponse
    {
        $client = $request->attributes->get('client');

        return $this->json([
            'success' => true,
            'user' => $client->getName(),
            'limits' => [
                'maxLimit' => $client->getMaxLimitFor('bar'),
                'interval' => $client->getIntervalFor('bar'),
            ],
        ]);
    }
}
