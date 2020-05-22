<?php

declare(strict_types=1);

namespace Tickets\Repository;

use Marcosh\LamPHPda\Maybe;
use Tickets\Domain\Id;
use Tickets\Domain\Ticket;

interface TicketsRepository
{
    /**
     * @param Id $ticketId
     * @psalm-param Id<Ticket> $ticketId
     * @return Maybe
     * @psalm-return Maybe<Ticket>
     */
    public function loadTicket(Id $ticketId): Maybe;
}
