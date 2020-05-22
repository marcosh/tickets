<?php

declare(strict_types=1);

namespace Tickets\Service;

use Tickets\Domain\Ticket;

final class CompositeNotifier implements Notifier
{
    /** @var Notifier[] */
    private $notifiers;

    /**
     * @param Notifier[] $notifiers
     */
    public function __construct(array $notifiers)
    {
        $this->notifiers = $notifiers;
    }

    public function notifyUsersOfTicket(array $users, Ticket $ticket): void
    {
        foreach ($this->notifiers as $notifier) {
            $notifier->notifyUsersOfTicket($users, $ticket);
        }
    }
}
