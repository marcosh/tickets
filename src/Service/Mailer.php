<?php

declare(strict_types=1);

namespace Tickets\Service;

use Tickets\Domain\User;

interface Mailer
{
    public function sendEmail(User $user, string $title, string $body): void;
}
