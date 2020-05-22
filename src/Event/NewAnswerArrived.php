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
final class NewAnswerArrived implements Event
{
    /** @var Id<Ticket> */
    private $ticketId;

    /** @var Message */
    private $message;

    /** @var \DateTimeImmutable */
    private $answeredAt;

    /**
     * @param Id $ticketId
     * @psalm-param Id<Ticket> $ticketId
     * @param Message $message
     * @param \DateTimeImmutable $answeredAt
     * @psalm-pure
     */
    public function __construct(Id $ticketId, Message $message, \DateTimeImmutable $answeredAt)
    {
        /** @psalm-suppress ImpurePropertyAssignment */
        $this->ticketId = $ticketId;
        $this->message = $message;
        $this->answeredAt = $answeredAt;
    }

    /**
     * @return Id
     * @psalm-return Id<Ticket>
     * @psalm-pure
     */
    public function ticketId(): Id
    {
        return $this->ticketId;
    }

    /**
     * @return Message
     * @psalm-pure
     */
    public function message(): Message
    {
        return $this->message;
    }

    /**
     * @return User
     * @psalm-pure
     */
    public function user(): User
    {
        return $this->message->user();
    }

    /**
     * @return \DateTimeImmutable
     * @psalm-pure
     */
    public function answeredAt(): \DateTimeImmutable
    {
        return $this->answeredAt;
    }
}
