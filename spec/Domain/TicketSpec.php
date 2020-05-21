<?php

declare(strict_types=1);

namespace TicketsSpec\Domain;

use Lcobucci\Clock\FrozenClock;
use Marcosh\LamPHPda\Maybe;
use Ramsey\Uuid\Uuid;
use Tickets\Domain\Id;
use Tickets\Domain\Message;
use Tickets\Domain\Ticket;
use Tickets\Domain\User;
use Tickets\Event\TicketOpened;

describe('Ticket', function () {
    it('emits TicketOpen when opened', function () {
        $ticketId = Id::fromUuid(Uuid::uuid4());
        $message = Message::fromUserAndBody(
            User::withIdAndProfile(
                Id::fromUuid(Uuid::uuid4()),
                new User\Common()
            ),
            'a message'
        );
        $now = date_create_immutable();
        $clock = new FrozenClock($now);

        $events = Ticket::open(
            $ticketId,
            $message,
            $clock
        );

        expect($events)->toEqual([
            new TicketOpened(
                $ticketId,
                $message,
                $now
            )
        ]);
    });

    it('rebuilds the aggregate correctly from a TicketOpen event', function () {
        $ticketId = Id::fromUuid(Uuid::uuid4());
        $user = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Common()
        );
        $message = Message::fromUserAndBody($user, 'a message');
        $now = date_create_immutable();

        $event = new TicketOpened(
            $ticketId,
            $message,
            $now
        );

        $ticket = Ticket::onTicketOpened($event);

        expect($ticket->ticketId())->toBe($ticketId);
        expect($ticket->openedAt())->toBe($now);
        expect($ticket->lastEditedAt())->toBe($now);
        expect($ticket->openedBy())->toBe($user);
        expect($ticket->assignedTo())->toEqual(Maybe::nothing());
        expect($ticket->messages())->toBe([$message]);
        expect($ticket->status())->toEqual(Ticket\Status::new());
    });
});
