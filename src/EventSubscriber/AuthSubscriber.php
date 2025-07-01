<?php

namespace App\EventSubscriber;

use App\Repository\ClientRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class AuthSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ClientRepository $clientRepository
    ) {
    }

    public function onRequestEvent(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !preg_match('/^Bearer (.+)$/i', $authHeader, $matches)) {
            $event->setResponse(new JsonResponse(['error' => 'Unauthorized'], 401));
            return;
        }

        $clientId = $matches[1];
        $client = $this->clientRepository->find($clientId);

        if (!$client) {
            $event->setResponse(new JsonResponse(['error' => 'Unauthorized'], 401));
            return;
        }

        $request->attributes->set('client', $client);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onRequestEvent',
        ];
    }
}
