<?php

declare(strict_types=1);

namespace Tickets\CommandHandler;

use Lcobucci\Clock\Clock;
use Marcosh\LamPHPda\Either;
use Tickets\Command\AnswerTicket;
use Tickets\Domain\Ticket;
use Tickets\Error\AnswerError;
use Tickets\Event\Event;
use Tickets\Repository\TicketsRepository;

final class HandleAnswerTicket
{
    /** @var TicketsRepository */
    private $ticketsRepository;

    /** @var Clock */
    private $clock;

    /**
     * @param TicketsRepository $ticketsRepository
     * @param Clock $clock
     * @psalm-pure
     */
    public function __construct(TicketsRepository $ticketsRepository, Clock $clock)
    {
        $this->ticketsRepository = $ticketsRepository;
        $this->clock = $clock;
    }

    /**
     * @param AnswerTicket $answerTicket
     * @return Either
     * @psalm-return Either<AnswerError, Event[]>
     */
    public function handle(AnswerTicket $answerTicket): Either
    {
        $maybeTicket = $this->ticketsRepository->loadTicket($answerTicket->ticketId());

        /** @psalm-var Either<AnswerError, Event[]> $ifNoTicket */
        $ifNoTicket = Either::left(AnswerError::ticketNotFound());

        return $maybeTicket->eval(
            $ifNoTicket,
            (/**
             * @param Ticket $ticket
             * @psalm-return Either<AnswerError, Event[]>
             */
            fn($ticket) => $ticket->answer($answerTicket->message(), $this->clock))
        );
    }
}
