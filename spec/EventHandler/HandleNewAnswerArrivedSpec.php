<?php

declare(strict_types=1);

namespace TicketsSpec\EventHandler;

use Ramsey\Uuid\Uuid;
use Tickets\Domain\Id;
use Tickets\Domain\Message;
use Tickets\Domain\Ticket;
use Tickets\Domain\User;
use Tickets\Event\NewAnswerArrived;
use Tickets\Event\TicketOpened;
use Tickets\EventHandler\HandleNewAnswerArrived;
use Tickets\Repository\SingleTicketRepository;
use Tickets\Service\InMemoryNotifier;

describe('HandleNewAnswerArrived', function () {
    it('notifies no one if user answer unassigned ticket', function () {
        $ticketId = Id::fromUuid(Uuid::uuid4());
        $user = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Common()
        );
        $message = Message::fromUserAndBody($user, 'a message');
        $then = date_create_immutable();
        $ticketOpened = new TicketOpened(
            $ticketId,
            $message,
            $then
        );
        $ticket = Ticket::onTicketOpened($ticketOpened);

        $ticketsRepository = SingleTicketRepository::withTicket($ticket);

        $notifier = new InMemoryNotifier();

        $handler = new HandleNewAnswerArrived(
            $ticketsRepository,
            $notifier
        );

        $answerMessage = Message::fromUserAndBody($user, 'an answer');
        $now = date_create_immutable();
        $event = new NewAnswerArrived(
            $ticketId,
            $answerMessage,
            $now
        );

        $handler->handle($event);

        expect($notifier->notifications())->toBeEmpty();
    });


    it('notifies the user if an admin answers', function () {
        $ticketId = Id::fromUuid(Uuid::uuid4());
        $user = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Common()
        );
        $message = Message::fromUserAndBody($user, 'a message');
        $then = date_create_immutable();
        $ticketOpened = new TicketOpened(
            $ticketId,
            $message,
            $then
        );
        $ticket = Ticket::onTicketOpened($ticketOpened);

        $ticketsRepository = SingleTicketRepository::withTicket($ticket);

        $notifier = new InMemoryNotifier();

        $handler = new HandleNewAnswerArrived(
            $ticketsRepository,
            $notifier
        );

        $admin = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Admin()
        );
        $answerMessage = Message::fromUserAndBody($admin, 'an answer');
        $now = date_create_immutable();
        $event = new NewAnswerArrived(
            $ticketId,
            $answerMessage,
            $now
        );

        $handler->handle($event);

        expect($notifier->notifications())->toBe([
            [
                "user" => $user,
                "ticket" => $ticket
            ]
        ]);
    });

    it('notifies the assignee if user answers assigned ticket', function () {
        $ticketId = Id::fromUuid(Uuid::uuid4());
        $user = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Common()
        );
        $message = Message::fromUserAndBody($user, 'a message');
        $then = date_create_immutable();
        $ticketOpened = new TicketOpened(
            $ticketId,
            $message,
            $then
        );
        $ticket = Ticket::onTicketOpened($ticketOpened);

        $admin = User::withIdAndProfile(
            Id::fromUuid(Uuid::uuid4()),
            new User\Admin()
        );
        $answerMessage = Message::fromUserAndBody($admin, 'an answer');
        $now = date_create_immutable();
        $event = new NewAnswerArrived(
            $ticketId,
            $answerMessage,
            $now
        );

        $ticket = $ticket->onNewAnswerArrived($event);

        $otherAnswerMessage = Message::fromUserAndBody($user, 'other answer');
        $otherNow = date_create_immutable();
        $newEvent = new NewAnswerArrived(
            $ticketId,
            $otherAnswerMessage,
            $otherNow
        );

        $ticketsRepository = SingleTicketRepository::withTicket($ticket);

        $notifier = new InMemoryNotifier();

        $handler = new HandleNewAnswerArrived(
            $ticketsRepository,
            $notifier
        );

        $handler->handle($newEvent);

        expect($notifier->notifications())->toBe([
            [
                "user" => $admin,
                "ticket" => $ticket
            ]
        ]);
    });
});
