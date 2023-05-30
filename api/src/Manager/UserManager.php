<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use DateTimeImmutable;
use Overblog\GraphQLBundle\Definition\Argument;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserManager
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function create(Argument $arguments): User
    {
        return $this->createOrUpdate($arguments);
    }

    public function update(Argument $arguments, User $user): User
    {
        return $this->createOrUpdate($arguments, $user);
    }

    public function changePassword(Argument $arguments, User $user): User
    {
        $input = $arguments->offsetGet('password');
        assert(is_array($input));

        return $user->setPassword($this->userPasswordHasher->hashPassword($user, $input['new']));
    }

    public function updateAccount(Argument $arguments, User $user): User
    {
        $input = $arguments->offsetGet('user');
        assert(is_array($input));

        return $user
            ->setName($input['name'])
            ->setEmail($input['email']);
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
            ->setRoles($input['roles'])
            ->setPassword($input['password'])
            ->setCreatedAt($user ? $user->getCreatedAt() : new DateTimeImmutable())
            ->setUpdatedAt($user ? $user->getUpdatedAt() : new DateTimeImmutable());
    }
}
