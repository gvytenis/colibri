<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Category;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public const REFERENCE_CATEGORY_1 = 'CATEGORY_1';

    public const REFERENCE_CATEGORY_2 = 'CATEGORY_2';

    public function load(ObjectManager $manager): void
    {
        $category1 = (new Category())
            ->setName('Programming')
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $category2 = (new Category())
            ->setName('Architecture')
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $this->setReference(self::REFERENCE_CATEGORY_1, $category1);
        $this->setReference(self::REFERENCE_CATEGORY_2, $category2);

        $manager->persist($category1);
        $manager->persist($category2);

        $manager->flush();
    }
}
