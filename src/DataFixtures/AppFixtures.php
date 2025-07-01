<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\ClientRateLimit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client1 = new Client();
        $client1->setName('John Doe');

        $client2 = new Client();
        $client2->setName('Jill Moe');

        $clientRateLimit1 = new ClientRateLimit();
        $clientRateLimit1->setClient($client1);
        $clientRateLimit1->setMaxLimit(10);
        $clientRateLimit1->setInterval(60);
        $clientRateLimit1->setEndpoint('foo');

        $clientRateLimit2 = new ClientRateLimit();
        $clientRateLimit2->setClient($client1);
        $clientRateLimit2->setMaxLimit(5);
        $clientRateLimit2->setInterval(60);
        $clientRateLimit2->setEndpoint('bar');

        $clientRateLimit3 = new ClientRateLimit();
        $clientRateLimit3->setClient($client2);
        $clientRateLimit3->setMaxLimit(3);
        $clientRateLimit3->setInterval(30);
        $clientRateLimit3->setEndpoint('foo');

        $clientRateLimit4 = new ClientRateLimit();
        $clientRateLimit4->setClient($client2);
        $clientRateLimit4->setMaxLimit(2);
        $clientRateLimit4->setInterval(30);
        $clientRateLimit4->setEndpoint('bar');

        $manager->persist($client1);
        $manager->persist($client2);
        $manager->persist($clientRateLimit1);
        $manager->persist($clientRateLimit2);
        $manager->persist($clientRateLimit3);
        $manager->persist($clientRateLimit4);
        $manager->flush();

        $manager->flush();
    }
}
