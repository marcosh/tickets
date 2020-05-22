<?php

declare(strict_types=1);

namespace Tickets\Error;

/**
 * @psalm-immutable
 */
final class AnswerError
{
    private const ADMIN_CAN_ANSWER_ONLY_NEW_OR_ASSIGNED_TICKET = 0;
    private const USER_CAN_ANSWER_ONLY_TO_TICKETS_HE_CREATED = 1;

    /** @var int */
    private $option;

    /**
     * @param int $option
     * @psalm-pure
     */
    private function __construct(int $option)
    {
        $this->option = $option;
    }

    /**
     * @return AnswerError
     * @psalm-pure
     */
    public static function adminCanAnswerOnlyNewOrAssignedTickets(): self
    {
        return new self(self::ADMIN_CAN_ANSWER_ONLY_NEW_OR_ASSIGNED_TICKET);
    }

    /**
     * @return AnswerError
     * @psalm-pure
     */
    public static function userCanAnswerOnlyToTicketsHeCreated(): self
    {
        return new self(self::USER_CAN_ANSWER_ONLY_TO_TICKETS_HE_CREATED);
    }
}
