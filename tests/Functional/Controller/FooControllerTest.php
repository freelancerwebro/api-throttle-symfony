<?php

declare(strict_types=1);

namespace Functional\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FooControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->client = static::createClient();

        parent::setUp();

        $em = static::getContainer()->get(EntityManagerInterface::class);
        $em->createQuery('DELETE FROM App\Entity\RateLimitEntry')->execute();
    }

    public function testFooAllowsWithinTokenBucketLimit(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->client->request('GET', '/foo', [], [], [
                'HTTP_Authorization' => 'Bearer 1',
            ]);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
            $this->assertJsonStringEqualsJsonString(
                json_encode(['success' => true]),
                $this->client->getResponse()->getContent()
            );
        }
    }

    public function testFooBlocksAfterTokenBucketExceeds(): void
    {
        for ($i = 0; $i < 11; $i++) {
            $this->client->request('GET', '/foo', [], [], [
                'HTTP_Authorization' => 'Bearer 1',
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
        $this->client->request('GET', '/foo');

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }
}
