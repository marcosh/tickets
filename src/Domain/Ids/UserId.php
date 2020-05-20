<?php

declare(strict_types=1);

namespace Tickets\Domain\Ids;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-immutable
 */
final class UserId
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
     * @return UserId
     */
    public static function generate(): self
    {
        $uuid = Uuid::uuid4();

        return new self($uuid);
    }

    /**
     * @param UuidInterface $uuid
     * @return UserId
     * @psalm-pure
     */
    public static function fromUuid(UuidInterface $uuid): self
    {
        return new self($uuid);
    }
}
