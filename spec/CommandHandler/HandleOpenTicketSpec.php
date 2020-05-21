<?php

declare(strict_types=1);

namespace TicketsSpec\Commandhandler;

use Lcobucci\Clock\FrozenClock;
use Ramsey\Uuid\Uuid;
use Tickets\Command\OpenTicket;
use Tickets\CommandHandler\HandleOpenTicket;
use Tickets\Domain\Id;
use Tickets\Domain\Message;
use Tickets\Domain\User;
use Tickets\Event\TicketOpened;
use Tickets\Service\ConstantIdGenerator;

describe('HandleOpenTicket', function () {
    it('emits TicketOpen event', function () {
        $now = date_create_immutable();
        $clock = new FrozenClock($now);

        $uuid = Uuid::uuid4();
        $ticketId = Id::fromUuid($uuid);
        $idGenerator = new ConstantIdGenerator($uuid);

        $handler = new HandleOpenTicket($clock, $idGenerator);

        $user = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Common()
        );

        $message = Message::fromUserAndBody(
            $user,
            'a message'
        );
        $command = OpenTicket::withMessage($message);

        $events = $handler->handle($command);

        expect($events)->toEqual([
            new TicketOpened(
                $ticketId,
                $message,
                $now
            )
        ]);
    });
});
