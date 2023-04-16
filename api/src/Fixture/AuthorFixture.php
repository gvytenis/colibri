<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Author;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixture extends Fixture
{
    public const REFERENCE_AUTHOR_1 = 'AUTHOR_1';

    public const REFERENCE_AUTHOR_2 = 'AUTHOR_2';

    public function load(ObjectManager $manager): void
    {
        $author1 = (new Author())
            ->setName('Eric Evans')
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $author2 = (new Author())
            ->setName('Kent Beck')
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $this->setReference(self::REFERENCE_AUTHOR_1, $author1);
        $this->setReference(self::REFERENCE_AUTHOR_2, $author2);

        $manager->persist($author1);
        $manager->persist($author2);

        $manager->flush();
    }
}
