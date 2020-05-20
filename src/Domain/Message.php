<?php

declare(strict_types=1);

namespace Tickets\Domain;

/**
 * @psalm-immutable
 */
final class Message
{
    /** @var Id<User> */
    private $userId;

    /** @var string */
    private $body;

    /**
     * @param Id $userId
     * @psalm-param Id<User> $userId
     * @param string $body
     */
    private function __construct(Id $userId, string $body)
    {
        $this->userId = $userId;
        $this->body = $body;
    }

    /**
     * @param Id $userId
     * @psalm-param Id<User> $userId
     * @param string $body
     * @return Message
     */
    public static function fromUserIdAndBody(Id $userId, string $body): self
    {
        return new self($userId, $body);
    }
}
