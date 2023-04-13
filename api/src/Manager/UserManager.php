<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use Overblog\GraphQLBundle\Definition\Argument;

final class UserManager
{
    public function create(Argument $arguments): User
    {
        return $this->createOrUpdate($arguments);
    }

    public function update(Argument $arguments, User $user): User
    {
        return $this->createOrUpdate($arguments, $user);
    }

    private function createOrUpdate(Argument $arguments, User $user = null): User
    {
        $input = $arguments->offsetGet('user');
        assert(is_array($input));

        return ($user ?? new User())
            ->setName($input['name'])
            ->setUsername($input['username'])
            ->setEmail($input['email'])
            ->setStatus($input['status'])
            ->setRoles($input['roles']);
    }
}
