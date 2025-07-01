<?php

declare(strict_types=1);

namespace Functional\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BarControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->client = static::createClient();

        parent::setUp();

        $em = static::getContainer()->get(EntityManagerInterface::class);
        $em->createQuery('DELETE FROM App\Entity\RateLimitEntry')->execute();
    }

    public function testBarAllowsWithinFixedWindowLimit(): void
    {
        for ($i = 0; $i < 2; $i++) {
            $this->client->request('GET', '/bar', [], [], [
                'HTTP_Authorization' => 'Bearer 2',
            ]);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testBarBlocksWhenFixedWindowLimitExceeded(): void
    {
        // assuming client2 has limit of 2 requests per 30 seconds
        for ($i = 0; $i < 3; $i++) {
            $this->client->request('GET', '/bar', [], [], [
                'HTTP_Authorization' => 'Bearer 2',
            ]);
        }

        $this->assertEquals(429, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'rate limit exceeded']),
            $this->client->getResponse()->getContent()
        );
    }

    public function testUnauthorizedIfMissingAuthHeader(): void
    {
        $this->client->request('GET', '/bar');

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }
}
