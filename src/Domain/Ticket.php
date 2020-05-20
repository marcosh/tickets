<?php

declare(strict_types=1);

namespace Tickets\Domain;

use Marcosh\LamPHPda\Maybe;
use Tickets\Domain\User\Admin;

/**
 * @psalm-immutable
 */
final class Ticket
{
    /** @var \DateTimeImmutable */
    private $openedAt;

    /** @var \DateTimeImmutable */
    private $lastEditedAt;

    /** @var User */
    private $openedBy;

    /** @psalm-var Maybe<User<Admin>> */
    private $assignedTo;

    /**
     * @param \DateTimeImmutable $openedAt
     * @param \DateTimeImmutable $lastEditedAt
     * @param User $openedBy
     * @psalm-param Maybe<User<Admin>> $assignedTo
     */
    private function __construct(
        \DateTimeImmutable $openedAt,
        \DateTimeImmutable $lastEditedAt,
        User $openedBy,
        Maybe $assignedTo
    ) {
        $this->openedAt = $openedAt;
        $this->lastEditedAt = $lastEditedAt;
        $this->openedBy = $openedBy;
        $this->assignedTo = $assignedTo;
    }
}
