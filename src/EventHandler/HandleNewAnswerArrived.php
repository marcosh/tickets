<?php

declare(strict_types=1);

namespace Tickets\EventHandler;

use Tickets\Domain\Ticket;
use Tickets\Domain\User;
use Tickets\Event\NewAnswerArrived;
use Tickets\Repository\TicketsRepository;
use Tickets\Service\Notifier;

final class HandleNewAnswerArrived
{
    /** @var TicketsRepository */
    private $ticketsRepository;

    /** @var Notifier */
    private $notifier;

    public function __construct(TicketsRepository $ticketsRepository, Notifier $notifier)
    {
        $this->ticketsRepository = $ticketsRepository;
        $this->notifier = $notifier;
    }

    public function handle(NewAnswerArrived $event): void
    {
        $maybeTicket = $this->ticketsRepository->loadTicket($event->ticketId());

        $answerBy = $event->user();

        $maybeTicket->eval(
            null,
            function (Ticket $ticket) use ($answerBy) {
                if ($answerBy->isAdmin()) {
                    $this->notifier->notifyUsersOfTicket([$ticket->openedBy()], $ticket);
                }

                $ticket->assignedTo()->eval(
                    null,
                    function (User $admin) use ($ticket) {
                        $this->notifier->notifyUsersOfTicket([$admin], $ticket);
                    }
                );
            }
        );
    }
}
