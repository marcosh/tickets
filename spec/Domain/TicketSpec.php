<?php

declare(strict_types=1);

namespace TicketsSpec\Domain;

use Lcobucci\Clock\FrozenClock;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\Maybe;
use Ramsey\Uuid\Uuid;
use Tickets\Domain\Id;
use Tickets\Domain\Message;
use Tickets\Domain\Ticket;
use Tickets\Domain\User;
use Tickets\Error\AnswerError;
use Tickets\Event\NewAnswerArrived;
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

    it('emits NewAnswerArrived when answer arrives from admin', function () {
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

        $admin = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Admin()
        );
        $answerMessage = Message::fromUserAndBody($admin, 'an answer');

        $now = date_create_immutable();
        $clock = new FrozenClock($now);

        $events = $ticket->answer($answerMessage, $clock);

        expect($events)->toEqual(Either::right([
            new NewAnswerArrived(
                $ticketId,
                $answerMessage,
                $now
            )
        ]));
    });

    it('emits NewAnswerArrived when answer arrives from user who opened the ticket', function () {
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

        $answerMessage = Message::fromUserAndBody($user, 'an answer');

        $now = date_create_immutable();
        $clock = new FrozenClock($now);

        $events = $ticket->answer($answerMessage, $clock);

        expect($events)->toEqual(Either::right([
            new NewAnswerArrived(
                $ticketId,
                $answerMessage,
                $now
            )
        ]));
    });

    it('returns an error when answer arrives from user who didn\'t open the ticket', function () {
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

        $anotherUser = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Common()
        );
        $answerMessage = Message::fromUserAndBody($anotherUser, 'an answer');

        $now = date_create_immutable();
        $clock = new FrozenClock($now);

        $error = $ticket->answer($answerMessage, $clock);

        expect($error)->toEqual(Either::left(AnswerError::userCanAnswerOnlyToTicketsHeCreated()));
    });

    it('returns an error when answer arrives from an admin who is not the assignee', function () {
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

        $admin = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Admin()
        );
        $answerMessage = Message::fromUserAndBody($admin, 'an answer');

        $now = date_create_immutable();
        $clock = new FrozenClock($now);

        $eitherEvents = $ticket->answer($answerMessage, $clock);

        $eitherEvents->eval(
            function ($error) {expect(1)->toBe(2);},
            function ($events) use ($ticket) {
                $ticket = $ticket->onNewAnswerArrived($events[0]);

                $anotherAdmin = User::withIdAndProfile(
                    Id::fromUuid(Uuid::uuid4()),
                    new User\Admin()
                );
                $answerMessage = Message::fromUserAndBody($anotherAdmin, 'an answer');

                $now = date_create_immutable();
                $clock = new FrozenClock($now);

                $error = $ticket->answer($answerMessage, $clock);

                expect($error)->toEqual(Either::left(AnswerError::adminCanAnswerOnlyNewOrAssignedTickets()));
            }
        );
    });
});
