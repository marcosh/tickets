<?php

declare(strict_types=1);

namespace TicketsSpec\EventHandler;

use Ramsey\Uuid\Uuid;
use Tickets\Domain\Id;
use Tickets\Domain\Message;
use Tickets\Domain\Ticket;
use Tickets\Domain\User;
use Tickets\Event\TicketOpened;
use Tickets\EventHandler\HandleTicketOpened;
use Tickets\Repository\ConstantUsersRepository;
use Tickets\Repository\SingleTicketRepository;
use Tickets\Service\InMemoryNotifier;

describe('HandleTicketOpened', function () {
    it('notifies all admins of ticket', function () {
        $admin1 = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Admin()
        );
        $admin2 = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Admin()
        );

        $userRepository = new ConstantUsersRepository([$admin1, $admin2]);

        $ticketId = Id::fromUuid(Uuid::uuid4());
        $user = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Common()
        );
        $message = Message::fromUserAndBody($user, 'a message');
        $then = date_create_immutable();
        $event = new TicketOpened(
            $ticketId,
            $message,
            $then
        );
        $ticket = Ticket::onTicketOpened($event);

        $ticketsRepository = SingleTicketRepository::withTicket($ticket);

        $notifier = new InMemoryNotifier();

        $handler = new HandleTicketOpened(
            $userRepository,
            $ticketsRepository,
            $notifier
        );

        $handler->handle($event);

        expect($notifier->notifications())->toBe([
            [
                "user" => $admin1,
                "ticket" => $ticket
            ],
            [
                "user" => $admin2,
                "ticket" => $ticket
            ]
        ]);
    });
});

