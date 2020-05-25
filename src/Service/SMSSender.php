<?php

declare(strict_types=1);

namespace Tickets\Service;

use Tickets\Domain\User;

interface SMSSender
{
    /**
     * @param User $user
     * @param string $body
     */
    public function sendSMS(User $user, string $body): void;
}
