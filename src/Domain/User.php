<?php

declare(strict_types=1);

namespace Tickets\Domain;

use Tickets\Domain\Ids\UserId;

/**
 * @psalm-immutable
 */
final class User
{
    /** @var UserId */
    private $userId;

    /** @var UserProfile */
    private $userProfile;

    /**
     * @param UserId $userId
     * @param UserProfile $userProfile
     * @psalm-pure
     */
    private function __construct(UserId $userId, UserProfile $userProfile)
    {
        $this->userId = $userId;
        $this->userProfile = $userProfile;
    }

    /**
     * @param UserId $userId
     * @param UserProfile $userProfile
     * @return User
     * @psalm-pure
     */
    public static function withIdAndProfile(UserId $userId, UserProfile $userProfile): self
    {
        return new self($userId, $userProfile);
    }
}
