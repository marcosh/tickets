<?php

declare(strict_types=1);

namespace Tickets\Command;

use Tickets\Domain\Message;
use Tickets\Domain\User;

/**
 * @psalm-immutable
 */
final class OpenTicket
{
    /** @var Message */
    private $message;

    /**
     * @param Message $message
     * @psalm-pure
     */
    private function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @param Message $message
     * @return OpenTicket
     * @psalm-pure
     */
    public function withMessage(Message $message): self
    {
        return new self($message);
    }
}
