<?php

declare(strict_types=1);

namespace Tickets\Domain;

use Tickets\Domain\User\UserId;
use Tickets\Domain\User\UserProfile;

/**
 * @template P of UserProfile
 *
 * @psalm-immutable
 */
final class User
{
    /** @var UserId */
    private $userId;

    /**
     * @var UserProfile
     * @psalm-var P
     */
    private $userProfile;

    /**
     * @param UserId $userId
     * @param UserProfile $userProfile
     * @psalm-param P $userProfile
     * @psalm-pure
     */
    private function __construct(UserId $userId, UserProfile $userProfile)
    {
        $this->userId = $userId;
        $this->userProfile = $userProfile;
    }

    /**
     * @template Q of UserProfile
     * @param UserId $userId
     * @param UserProfile $userProfile
     * @psalm-param Q $userProfile
     * @return User
     * @psalm-return User<Q>
     * @psalm-pure
     */
    public static function withIdAndProfile(UserId $userId, UserProfile $userProfile): self
    {
        return new self($userId, $userProfile);
    }
}
