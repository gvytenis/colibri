<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\CollectionArgumentProvider;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class BookResolver implements QueryInterface, AliasedInterface
{
    public function __construct(
        private BookRepository $bookRepository,
        private CollectionArgumentProvider $collectionArgumentProvider,
    ) {
    }

    public function get(Argument $arguments): ?Book
    {
        return $this->bookRepository->find(id: $arguments['id']);
    }

    public function getCollection(Argument $arguments): array
    {
        [$limit, $orderBy, $criteria] = $this->collectionArgumentProvider->provide($arguments);

        $books = $this->bookRepository->findBy(
            criteria: [],
            orderBy: [
                $orderBy => $criteria,
            ],
            limit: $limit,
            offset: 0
        );

        return [
            'books' => $books,
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
