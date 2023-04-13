<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Book;
use App\Repository\BookRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class BookResolver implements QueryInterface, AliasedInterface
{
    public function __construct(
        private BookRepository $bookRepository,
    ) {
    }

    public function get(Argument $arguments): ?Book
    {
        return $this->bookRepository->find(id: $arguments['id']);
    }

    public function getCollection(Argument $arguments): array
    {
        return [
            'books' => [],
        ];
    }

    public static function getAliases(): array
    {
        return [
            'get' => 'getBook',
            'getCollection' => 'getBooks',
        ];
    }
}
