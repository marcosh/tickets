<?php

declare(strict_types=1);

namespace Tickets\Service;

use Ramsey\Uuid\UuidInterface;
use Tickets\Domain\Id;

/**
 * @psalm-immutable
 */
final class ConstantIdGenerator implements IdGenerator
{
    private $uuid;

    /**
     * @param UuidInterface $uuid
     * @psalm-pure
     */
    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return Id
     * @psalm-pure
     */
    public function generateId(): Id
    {
        return Id::fromUuid($this->uuid);
    }
}
