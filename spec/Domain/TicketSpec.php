<?php

declare(strict_types=1);

namespace TicketsSpec\Domain;

use Lcobucci\Clock\FrozenClock;
use Tickets\Domain\Id;
use Tickets\Domain\Message;
use Tickets\Domain\Ticket;
use Tickets\Domain\User;
use Tickets\Event\TicketOpened;

describe('Ticket', function () {
    it('emits TicketOpen when opened', function () {
        $ticketId = Id::generate();
        $message = Message::fromUserAndBody(
            User::withIdAndProfile(
                Id::generate(),
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
