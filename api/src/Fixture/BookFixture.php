<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixture extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_BOOK_1 = 'BOOK_1';

    public const REFERENCE_BOOK_2 = 'BOOK_2';

    public function load(ObjectManager $manager): void
    {
        /** @var Author $author1 */
        $author1 = $this->getReference(AuthorFixture::REFERENCE_AUTHOR_1);

        /** @var Author $author2 */
        $author2 = $this->getReference(AuthorFixture::REFERENCE_AUTHOR_2);

        /** @var Author $author3 */
        $author3 = $this->getReference(AuthorFixture::REFERENCE_AUTHOR_3);

        /** @var Category $category */
        $category = $this->getReference(CategoryFixture::REFERENCE_CATEGORY_2);

        $book1 = (new Book())
            ->setTitle('Domain Driven Design')
            ->setAuthor($author1)
            ->setYear(2012)
            ->setCategory($category)
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $book2 = (new Book())
            ->setTitle('Implementation Patterns')
            ->setAuthor($author2)
            ->setYear(2000)
            ->setCategory($category)
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $book3 = (new Book())
            ->setTitle('Patterns of Enterprise Application Architecture')
            ->setAuthor($author3)
            ->setYear(2000)
            ->setCategory($category)
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $this->setReference(self::REFERENCE_BOOK_1, $book1);
        $this->setReference(self::REFERENCE_BOOK_2, $book2);

        $manager->persist($book1);
        $manager->persist($book2);
        $manager->persist($book3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AuthorFixture::class,
            CategoryFixture::class,
        ];
    }
}
