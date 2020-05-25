<?php

declare(strict_types=1);

namespace Tickets\Service;

use Tickets\Domain\Ticket;
use Tickets\Domain\User;

final class CompositeNotifier implements Notifier
{
    /** @var Notifier[] */
    private $notifiers;

    /**
     * @param Notifier[] $notifiers
     * @psalm-pure
     */
    public function __construct(array $notifiers)
    {
        $this->notifiers = $notifiers;
    }

    /**
     * @param User[] $users
     * @param Ticket $ticket
     */
    public function notifyUsersOfTicket(array $users, Ticket $ticket): void
    {
        foreach ($this->notifiers as $notifier) {
            $notifier->notifyUsersOfTicket($users, $ticket);
        }
    }
}
