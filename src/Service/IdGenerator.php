<?php

declare(strict_types=1);

namespace Tickets\Service;

use Tickets\Domain\Id;

interface IdGenerator
{
    public function generateId(): Id;
}
