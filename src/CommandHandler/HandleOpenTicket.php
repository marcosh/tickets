<?php

declare(strict_types=1);

namespace Tickets\CommandHandler;

use Lcobucci\Clock\Clock;
use Tickets\Command\OpenTicket;
use Tickets\Domain\Id;
use Tickets\Domain\Ticket;
use Tickets\Event\Event;
use Tickets\Service\IdGenerator;

final class HandleOpenTicket
{
    /** @var Clock */
    private $clock;

    /** @var IdGenerator */
    private $idGenerator;

    /**
     * @param Clock $clock
     * @param IdGenerator $idGenerator
     * @psalm-pure
     */
    public function __construct(Clock $clock, IdGenerator $idGenerator)
    {
        $this->clock = $clock;
        $this->idGenerator = $idGenerator;
    }

    /**
     * @param OpenTicket $openTicket
     * @return Event[]
     */
    public function handle(OpenTicket $openTicket): array
    {
        /** @psalm-var Id<Ticket> $ticketId */
        $ticketId = $this->idGenerator->generateId();

        return Ticket::open(
            $ticketId,
            $openTicket->message(),
            $this->clock
        );
    }
}
