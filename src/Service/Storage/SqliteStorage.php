<?php

declare(strict_types=1);

namespace App\Service\Storage;

use App\Entity\RateLimitEntry;
use Doctrine\ORM\EntityManagerInterface;

class SqliteStorage implements RateLimitStorageInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function get(string $key): mixed
    {
        /** @var RateLimitEntry|null $entry */
        $entry = $this->em->getRepository(RateLimitEntry::class)->find($key);

        if (!$entry) return null;

        if ($entry->getExpiresAt() && $entry->getExpiresAt() < new \DateTimeImmutable()) {
            $this->delete($key);
            return null;
        }

        return unserialize($entry->getFieldValue());
    }

    public function set(string $key, mixed $value, int $ttlInSeconds = null): void
    {
        $entry = $this->em->getRepository(RateLimitEntry::class)->find($key);
        if (!$entry) {
            $entry = new RateLimitEntry();
            $entry->setFieldKey($key);
        }

        $entry->setFieldValue(serialize($value));

        if ($ttlInSeconds !== null) {
            $entry->setExpiresAt(new \DateTimeImmutable()->add(new \DateInterval("PT{$ttlInSeconds}S")));
        } else {
            $entry->setExpiresAt(null);
        }

        $this->em->persist($entry);
        $this->em->flush();
    }

    public function delete(string $key): void
    {
        $entry = $this->em->getRepository(RateLimitEntry::class)->find($key);
        if ($entry) {
            $this->em->remove($entry);
            $this->em->flush();
        }
    }
}
