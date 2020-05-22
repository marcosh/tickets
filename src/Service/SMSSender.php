<?php

declare(strict_types=1);

namespace Tickets\Service;

use Tickets\Domain\User;

interface SMSSender
{
    public function sendSMS(User $user, string $body): void;
}
