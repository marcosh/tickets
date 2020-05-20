<?php

declare(strict_types=1);

namespace Tickets\Domain;

/**
 * @psalm-immutable
 */
final class User
{
    /** @var UserProfile */
    private $userProfile;

    /**
     * @param UserProfile $userProfile
     * @psalm-pure
     */
    private function __construct(UserProfile $userProfile)
    {
        $this->userProfile = $userProfile;
    }

    /**
     * @param UserProfile $userProfile
     * @return User
     * @psalm-pure
     */
    public static function withProfile(UserProfile $userProfile): self
    {
        return new self($userProfile);
    }
}
