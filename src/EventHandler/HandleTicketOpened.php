<?php

declare(strict_types=1);

namespace Tickets\EventHandler;

use Tickets\Event\TicketOpened;
use Tickets\Repository\TicketsRepository;
use Tickets\Repository\UsersRepository;
use Tickets\Service\Notifier;

final class HandleTicketOpened
{
    /**
     * @var UsersRepository
     */
    private $usersRepository;

    /**
     * @var TicketsRepository
     */
    private $ticketsRepository;

    /**
     * @var Notifier
     */
    private $notifier;

    private function __construct(
        UsersRepository $usersRepository,
        TicketsRepository $ticketsRepository,
        Notifier $notifier
    ) {
        $this->usersRepository = $usersRepository;
        $this->ticketsRepository = $ticketsRepository;
        $this->notifier = $notifier;
    }

    public function handle(TicketOpened $event): void
    {
        $allAdmins = $this->usersRepository->allAdmins();

        $maybeTicket = $this->ticketsRepository->loadTicket($event->ticketId());

        $maybeTicket->eval(
            null,
            (fn($ticket) => $this->notifier->notifyUsersOfTicket($allAdmins, $$ticket))
        );
    }
}
