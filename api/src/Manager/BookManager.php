<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\CategoryRepository;
use Overblog\GraphQLBundle\Definition\Argument;

final readonly class BookManager
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function create(Argument $arguments): Book
    {
        return $this->createOrUpdate($arguments);
    }

    public function update(Argument $arguments, Book $book): Book
    {
        return $this->createOrUpdate($arguments, $book);
    }

    private function createOrUpdate(Argument $arguments, Book $book = null): Book
    {
        $input = $arguments->offsetGet('book');
        assert(is_array($input));

        return ($book ?? new Book())
            ->setTitle($input['title'])
            ->setAuthor($this->authorRepository->find(id: $input['author']))
            ->setYear($input['year'])
            ->setCategory($this->categoryRepository->find(id: $input['category']));
    }
}
