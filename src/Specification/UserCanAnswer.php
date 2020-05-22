<?php

declare(strict_types=1);

namespace Tickets\Specification;

use Marcosh\LamPHPda\Maybe;
use Tickets\Domain\Ticket;
use Tickets\Domain\User;
use Tickets\Error\AnswerError;

final class UserCanAnswer
{
    /**
     * @param Ticket $ticket
     * @param User $user
     * @return Maybe
     * @psalm-return Maybe<AnswerError>
     */
    public function isSatisfiedBy(Ticket $ticket, User $user): Maybe
    {
        // if an admin created a ticket, he can answer it even if it is not new or assigned to him
        if ($ticket->wasCreatedBy($user)) {
            return Maybe::nothing();
        }

        if ($user->isAdmin()) {
            if ($ticket->isNew() || $ticket->isAssignedTo($user)) {
                return Maybe::nothing();
            }

            return Maybe::just(AnswerError::adminCanAnswerOnlyNewOrAssignedTickets());
        }

        return Maybe::just(AnswerError::userCanAnswerOnlyToTicketsHeCreated());
    }
}
