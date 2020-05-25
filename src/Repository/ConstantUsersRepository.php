<?php

declare(strict_types=1);

namespace Tickets\Repository;

use Tickets\Domain\User;
use Tickets\Domain\User\Admin;

/**
 * @psalm-immutable
 */
final class ConstantUsersRepository implements UsersRepository
{
    /** @var User[] */
    private $users;

    /**
     * @param User[] $users
     * @psalm-pure
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * @return User[]
     * @psalm-return User<Admin>[]
     * @psalm-pure
     */
    public function allAdmins(): array
    {
        return array_filter(
            $this->users,
            function (User $user) {
                return $user->isAdmin();
            }
        );
    }
}
