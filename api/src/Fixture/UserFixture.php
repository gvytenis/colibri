<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\User;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public const REFERENCE_USER_1 = 'USER_1';

    public const REFERENCE_USER_2 = 'USER_2';

    public function load(ObjectManager $manager): void
    {
        $user1 = (new User())
            ->setName('John Doe')
            ->setUsername('johndoe')
            ->setEmail('john@doe.test')
            ->setStatus('active')
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $user2 = (new User())
            ->setName('Jane Doe')
            ->setUsername('janedoe')
            ->setEmail('jane@doe.test')
            ->setStatus('active')
            ->setRoles(['ROLE_ADMIN'])
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $this->setReference(self::REFERENCE_USER_1, $user1);
        $this->setReference(self::REFERENCE_USER_2, $user2);

        $manager->persist($user1);
        $manager->persist($user2);

        $manager->flush();
    }
}
