<?php

declare(strict_types=1);

namespace Tickets\Domain;

use Tickets\Domain\User\Admin;
use Tickets\Domain\User\UserProfile;

/**
 * @template P of UserProfile
 *
 * @psalm-immutable
 */
final class User
{
    /**
     * @var Id
     * @psalm-var Id<User>
     */
    private $userId;

    /**
     * @var UserProfile
     * @psalm-var P
     */
    private $userProfile;

    /**
     * @param Id $userId
     * @psalm-param Id<User> $userId
     * @param UserProfile $userProfile
     * @psalm-param P $userProfile
     * @psalm-pure
     */
    private function __construct(Id $userId, UserProfile $userProfile)
    {
        $this->userId = $userId;
        $this->userProfile = $userProfile;
    }

    /**
     * @template Q of UserProfile
     * @param Id $userId
     * @psalm-param Id<User> $userId
     * @param UserProfile $userProfile
     * @psalm-param Q $userProfile
     * @return User
     * @psalm-return User<Q>
     * @psalm-pure
     */
    public static function withIdAndProfile(Id $userId, UserProfile $userProfile): self
    {
        return new self($userId, $userProfile);
    }

    /**
     * @return bool
     * @psalm-pure
     */
    public function isAdmin(): bool
    {
        return $this->userProfile instanceof Admin;
    }

    /**
     * @return Id
     * @psalm-return Id<User>
     */
    public function id(): Id
    {
        return $this->userId;
    }
}
