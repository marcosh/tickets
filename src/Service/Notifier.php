<?php

declare(strict_types=1);

namespace Tickets\Service;

use Tickets\Domain\Ticket;
use Tickets\Domain\User;

interface Notifier
{
    /**
     * @param User[] $users
     */
    public function notifyUsersOfTicket(array $users, Ticket $ticket): void;
}
