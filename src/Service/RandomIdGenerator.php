<?php

declare(strict_types=1);

namespace Tickets\Service;

use Ramsey\Uuid\Uuid;
use Tickets\Domain\Id;

final class RandomIdGenerator implements IdGenerator
{
    public function generateId(): Id
    {
        return Id::fromUuid(Uuid::uuid4());
    }
}
