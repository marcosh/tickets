<?php

declare(strict_types=1);

namespace TicketsSpec\Domain;

use Lcobucci\Clock\FrozenClock;
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
});
