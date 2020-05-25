<?php

declare(strict_types=1);

namespace Tickets\Domain\Ticket;

use Marcosh\LamPHPda\Maybe;
use Tickets\Domain\User;
use Tickets\Domain\User\Admin;

/**
 * @psalm-immutable
 */
final class Status
{
    private const NEW = 0;
    private const ASSIGNED = 1;
    private const CLOSED = 2;

    /** @var int */
    private $status;

    /**
     * The assignee is present only for the ASSIGNED case
     *
     * @var ?User
     * @psalm-var ?User<Admin>
     */
    private $assignee;

    /**
     * @param int $status
     * @psalm-pure
     */
    private function __construct(int $status, ?User $assignee = null)
    {
        $this->status = $status;
        $this->assignee = $assignee;
    }

    /**
     * @return Status
     * @psalm-pure
     */
    public static function new(): self
    {
        return new self(self::NEW);
    }

    /**
     * @param User $assignee
     * @psalm-param User $assignee
     * @return Status
     * @psalm-pure
     */
    public static function assigned(User $assignee): self
    {
        return new self(self::ASSIGNED, $assignee);
    }

    /**
     * @return Status
     * @psalm-pure
     */
    public static function closed(): self
    {
        return new self(self::CLOSED);
    }

    /**
     * @return bool
     * @psalm-pure
     */
    public function isNew(): bool
    {
        return $this->status === self::NEW;
    }

    /**
     * @return Maybe
     * @psalm-return Maybe<User<Admin>>
     */
    public function assignedTo(): Maybe
    {
        if (null === $this->assignee) {
            return Maybe::nothing();
        }

        return Maybe::just($this->assignee);
    }

    /**
     * @param User $user
     * @psalm-param User<Admin> $user
     * @return Status
     * @psalm-pure
     */
    public function adminAnswered(User $user): self
    {
        if ($this->isNew()) {
            return self::assigned($user);
        }

        return $this;
    }
}
