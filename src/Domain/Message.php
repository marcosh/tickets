<?php

declare(strict_types=1);

namespace Tickets\Domain;

/**
 * @psalm-immutable
 */
final class Message
{
    /** @var User */
    private $user;

    /** @var string */
    private $body;

    /**
     * @param User $user
     * @param string $body
     */
    private function __construct(User $user, string $body)
    {
        $this->user = $user;
        $this->body = $body;
    }

    /**
     * @param User $user
     * @param string $body
     * @return Message
     */
    public static function fromUserAndBody(User $user, string $body): self
    {
        return new self($user, $body);
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->user;
    }
}
