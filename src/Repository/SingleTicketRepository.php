<?php

declare(strict_types=1);

namespace Tickets\Repository;

use Marcosh\LamPHPda\Maybe;
use Tickets\Domain\Id;
use Tickets\Domain\Ticket;

/**
 * @psalm-immutable
 */
final class SingleTicketRepository implements TicketsRepository
{
    /** @var Ticket */
    private $ticket;

    /**
     * @param Ticket $ticket
     * @psalm-pure
     */
    private function __construct(Ticket $ticket)
    {
        /** @psalm-suppress ImpurePropertyAssignment */
        $this->ticket = $ticket;
    }

    /**
     * @param Ticket $ticket
     * @return SingleTicketRepository
     * @psalm-pure
     */
    public static function withTicket(Ticket $ticket): self
    {
        return new self($ticket);
    }

    /**
     * @param Id $ticketId
     * @psalm-param Id<Ticket> $ticketId
     * @return Maybe
     * @psalm-return Maybe<Ticket>
     * @psalm-pure
     */
    public function loadTicket(Id $ticketId): Maybe
    {
        if ($this->ticket->ticketId() != $ticketId) {
            return Maybe::nothing();
        }

        return Maybe::just($this->ticket);
    }
}
