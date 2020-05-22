<?php

declare(strict_types=1);

namespace TicketsSpec\CommandHandler;

use Lcobucci\Clock\FrozenClock;
use Marcosh\LamPHPda\Either;
use Ramsey\Uuid\Uuid;
use Tickets\Command\AnswerTicket;
use Tickets\CommandHandler\HandleAnswerTicket;
use Tickets\Domain\Id;
use Tickets\Domain\Message;
use Tickets\Domain\Ticket;
use Tickets\Domain\User;
use Tickets\Error\AnswerError;
use Tickets\Event\NewAnswerArrived;
use Tickets\Event\TicketOpened;
use Tickets\Repository\SingleTicketRepository;

describe('HandleNewAnswer', function () {
    it('emits TicketOpen event if the ticket is found', function () {
        $ticketId = Id::fromUuid(Uuid::uuid4());
        $user = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Common()
        );
        $message = Message::fromUserAndBody($user, 'a message');
        $then = date_create_immutable();

        $ticket = Ticket::onTicketOpened(
            new TicketOpened(
                $ticketId,
                $message,
                $then
            )
        );

        $ticketsRepository = SingleTicketRepository::withTicket($ticket);

        $now = date_create_immutable();
        $clock = new FrozenClock($now);

        $handler = new HandleAnswerTicket($ticketsRepository, $clock);

        $admin = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Admin()
        );
        $answerMessage = Message::fromUserAndBody($admin, 'an answer');

        $command = AnswerTicket::withTicketIdAndMessage(
            $ticketId,
            $answerMessage
        );

        $events = $handler->handle($command);

        expect($events)->toEqual(Either::right([
            new NewAnswerArrived(
                $ticketId,
                $answerMessage,
                $now
            )
        ]));
    });

    it('emits an error if the ticket is not found', function () {
        $ticketId = Id::fromUuid(Uuid::uuid4());
        $user = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Common()
        );
        $message = Message::fromUserAndBody($user, 'a message');
        $then = date_create_immutable();

        $ticket = Ticket::onTicketOpened(
            new TicketOpened(
                $ticketId,
                $message,
                $then
            )
        );

        $ticketsRepository = SingleTicketRepository::withTicket($ticket);

        $now = date_create_immutable();
        $clock = new FrozenClock($now);

        $handler = new HandleAnswerTicket($ticketsRepository, $clock);

        $admin = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Admin()
        );
        $answerMessage = Message::fromUserAndBody($admin, 'an answer');

        $otherTicketId = Id::fromUuid(Uuid::uuid4());
        $command = AnswerTicket::withTicketIdAndMessage(
            $otherTicketId,
            $answerMessage
        );

        $events = $handler->handle($command);

        expect($events)->toEqual(Either::left(AnswerError::ticketNotFound()));
    });
});
