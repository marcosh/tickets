<?php

declare(strict_types=1);

namespace Tickets\Service;

use Tickets\Domain\Ticket;
use Tickets\Domain\User;

final class SmsNotifier implements Notifier
{
    /** @var SMSSender */
    private $sender;

    /**
     * @param SMSSender $sender
     */
    public function __construct(SMSSender $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param User[] $users
     * @param Ticket $ticket
     */
    public function notifyUsersOfTicket(array $users, Ticket $ticket): void
    {
        foreach ($users as $user) {
            if ($user->wantsSMS()) {
                $this->sender->sendSMS($user, '');
            }
        }
    }
}
