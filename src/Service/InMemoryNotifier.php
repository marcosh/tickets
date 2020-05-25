<?php

declare(strict_types=1);

namespace Tickets\Service;

use Tickets\Domain\Ticket;
use Tickets\Domain\User;

final class InMemoryNotifier implements Notifier
{
    /** @var array{user: User, ticket: Ticket}[] */
    private $notifications = [];

    /**
     * @param User[] $users
     * @param Ticket $ticket
     */
    public function notifyUsersOfTicket(array $users, Ticket $ticket): void
    {
        foreach ($users as $user) {
            $this->notifications[] = [
                "user" => $user,
                "ticket" => $ticket
            ];
        }
    }

    /**
     * @return array
     * @psalm-return array{user: User, ticket: Ticket}[]
     * @psalm-pure
     */
    public function notifications(): array
    {
        return $this->notifications;
    }
}
