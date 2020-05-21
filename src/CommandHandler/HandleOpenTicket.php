<?php

declare(strict_types=1);

namespace Tickets\CommandHandler;

use Lcobucci\Clock\Clock;
use Tickets\Command\OpenTicket;
use Tickets\Domain\Id;
use Tickets\Domain\Ticket;
use Tickets\Event\Event;

final class HandleOpenTicket
{
    /** @var Clock */
    private $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    /**
     * @param OpenTicket $openTicket
     * @return Event[]
     */
    public function handle(OpenTicket $openTicket): array
    {
        /** @psalm-var Id<Ticket> $ticketId */
        $ticketId = Id::generate();

        return Ticket::open(
            $ticketId,
            $openTicket->message(),
            $this->clock
        );
    }
}
