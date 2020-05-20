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

    private function __construct(int $status)
    {
        $this->status = $status;
    }

    public static function new(): self
    {
        return new self(self::NEW);
    }

    public static function assigned(): self
    {
        return new self(self::ASSIGNED);
    }

    public static function closed(): self
    {
        return new self(self::CLOSED);
    }
}
