<?php

declare(strict_types=1);

namespace Tickets\Service;

use Tickets\Domain\Ticket;
use Tickets\Domain\User;

final class EmailNotifier implements Notifier
{
    /** @var Mailer */
    private $mailer;

    /**
     * @param Mailer $mailer
     * @psalm-pure
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param User[] $users
     * @param Ticket $ticket
     */
    public function notifyUsersOfTicket(array $users, Ticket $ticket): void
    {
        foreach ($users as $user) {
            $this->mailer->sendEmail($user, '', '');
        }
    }
}
