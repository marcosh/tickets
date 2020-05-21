<?php

declare(strict_types=1);

namespace Tickets\Domain;

use Lcobucci\Clock\Clock;
use Marcosh\LamPHPda\Maybe;
use Tickets\Domain\User\Admin;
use Tickets\Event\Event;
use Tickets\Event\TicketOpened;

/**
 * @psalm-immutable
 */
final class Ticket
{
    /**
     * @var Id
     * @psalm-var Id<Ticket>
     */
    private $ticketId;

    /** @var \DateTimeImmutable */
    private $openedAt;

    /** @var \DateTimeImmutable */
    private $lastEditedAt;

    /** @var User */
    private $openedBy;

    /** @psalm-var Maybe<User<Admin>> */
    private $assignedTo;

    /**
     * @var Message[]
     * @psalm-var non-empty-list<Message>
     */
    private $messages;

    /**
     * @param Id $ticketId
     * @psalm-param Id<Ticket> $ticketId
     * @param \DateTimeImmutable $openedAt
     * @param \DateTimeImmutable $lastEditedAt
     * @param User $openedBy
     * @param Maybe $assignedTo
     * @psalm-param Maybe<User<Admin>> $assignedTo
     * @param Message[] $messages
     * @psalm-param non-empty-list<Message> $messages
     * @psalm-pure
     */
    private function __construct(
        Id $ticketId,
        \DateTimeImmutable $openedAt,
        \DateTimeImmutable $lastEditedAt,
        User $openedBy,
        Maybe $assignedTo,
        array $messages
    ) {
        $this->ticketId = $ticketId;
        $this->openedAt = $openedAt;
        $this->lastEditedAt = $lastEditedAt;
        $this->openedBy = $openedBy;
        $this->assignedTo = $assignedTo;
        $this->messages = $messages;
    }

    /**
     * @param Id $ticketId
     * @psalm-param Id<Ticket> $ticketId
     * @param Message $message
     * @param Clock $clock
     * @return Event[]
     */
    public static function open(
        Id $ticketId,
        Message $message,
        Clock $clock
    ): array {
        /** @psalm-suppress ImpureMethodCall */
        $openedAt = $clock->now();

        return [
            new TicketOpened(
                $ticketId,
                $message,
                $openedAt
            )
        ];
    }

    /**
     * @param TicketOpened $event
     * @return Ticket
     * @psalm-pure
     */
    public static function onTicketOpened(TicketOpened $event): self
    {
        return new self(
            $event->ticketId(),
            $event->openedAt(),
            $event->openedAt(),
            $event->user(),
            Maybe::nothing(),
            [
                $event->message()
            ]
        );
    }
}
