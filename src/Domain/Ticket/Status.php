<?php

declare(strict_types=1);

namespace Tickets\Domain\Ticket;

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
     * @param int $status
     * @psalm-pure
     */
    private function __construct(int $status)
    {
        $this->status = $status;
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
     * @return Status
     * @psalm-pure
     */
    public static function assigned(): self
    {
        return new self(self::ASSIGNED);
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
     * @return Status
     * @psalm-pure
     */
    public function adminAnswered(): self
    {
        if ($this->isNew()) {
            return self::assigned();
        }

        return $this;
    }
}
