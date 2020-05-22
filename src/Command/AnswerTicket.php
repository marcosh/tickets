<?php

declare(strict_types=1);

namespace Tickets\Command;

use Tickets\Domain\Id;
use Tickets\Domain\Message;
use Tickets\Domain\Ticket;

/**
 * @psalm-immutable
 */
final class AnswerTicket
{
    /** @var Id<Ticket> */
    private $ticketId;

    /** @var Message */
    private $message;

    /**
     * @param Id $ticketId
     * @psalm-param Id<Ticket> $ticketId
     * @param Message $message
     * @psalm-pure
     */
    private function __construct(Id $ticketId, Message $message)
    {
        /** @psalm-suppress ImpurePropertyAssignment */
        $this->ticketId = $ticketId;
        $this->message = $message;
    }

    /**
     * @param Id $ticketId
     * @psalm-param Id<Ticket> $ticketId
     * @param Message $message
     * @return AnswerTicket
     * @psalm-pure
     */
    public static function withTicketIdAndMessage(Id $ticketId, Message $message): self
    {
        return new self($ticketId, $message);
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
     * @psalm-pure
     */
    public function message(): Message
    {
        return $this->message;
    }
}
