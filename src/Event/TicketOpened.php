<?php

declare(strict_types=1);

namespace Tickets\Event;

use Tickets\Domain\Id;
use Tickets\Domain\Message;
use Tickets\Domain\Ticket;
use Tickets\Domain\User;

/**
 * @psalm-immutable
 */
final class TicketOpened implements Event
{
    /** @var Id<Ticket> */
    private $ticketId;

    /** @var Message */
    private $message;

    /** @var \DateTimeImmutable */
    private $openedAt;

    /**
     * @param Id $ticketId
     * @psalm-param Id<Ticket> $ticketId
     * @param Message $message
     * @param \DateTimeImmutable $openedAt
     * @psalm-pure
     */
    public function __construct(Id $ticketId, Message $message, \DateTimeImmutable $openedAt)
    {
        $this->ticketId = $ticketId;
        $this->message = $message;
        $this->openedAt = $openedAt;
    }

    /**
     * @return Id
     * @psalm-return Id<Ticket>
     */
    public function ticketId(): Id
    {
        return $this->ticketId;
    }

    /**
     * @return Message
     */
    public function message(): Message
    {
        return $this->message;
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->message->user();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function openedAt(): \DateTimeImmutable
    {
        return $this->openedAt;
    }
}
