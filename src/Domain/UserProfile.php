<?php

declare(strict_types=1);

namespace Tickets\Domain;

/**
 * @psalm-immutable
 */
final class UserProfile
{
    private const ADMIN = 0;
    private const COMMON = 1;

    /** @var int */
    private $role;

    /**
     * @param int $role
     * @psalm-pure
     */
    private function __construct(int $role)
    {
        $this->role = $role;
    }

    /**
     * @return UserProfile
     * @psalm-pure
     */
    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    /**
     * @return UserProfile
     * @psalm-pure
     */
    public static function common(): self
    {
        return new self(self::COMMON);
    }
}
