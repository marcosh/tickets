<?php

declare(strict_types=1);

namespace Tickets\Repository;

use Tickets\Domain\User;
use Tickets\Domain\User\Admin;

interface UsersRepository
{
    /**
     * @return User[]
     * @psalm-return User<Admin>[]
     */
    public function allAdmins(): array;
}
