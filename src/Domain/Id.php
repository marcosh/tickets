<?php

declare(strict_types=1);

namespace Tickets\Domain;

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
     * @param UuidInterface $uuid
     * @return Id
     * @psalm-pure
     */
    public static function fromUuid(UuidInterface $uuid): self
    {
        return new self($uuid);
    }
}
