<?php

declare(strict_types=1);

namespace Tickets\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @template A
 *
 * @psalm-immutable
 */
final class Id
{
    /** @var UuidInterface */
    private $uuid;

    /**
     * @param UuidInterface $uuid
     * @psalm-pure
     */
    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return Id
     */
    public static function generate(): self
    {
        $uuid = Uuid::uuid4();

        return new self($uuid);
    }

    /**
     * @param UuidInterface $uuid
     * @return Id
     * @psalm-pure
     */
    public static function fromUuid(UuidInterface $uuid): self
    {
        return new self($uuid);
    }
}
