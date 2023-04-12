<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Service\CollectionArgumentProvider;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class AuthorResolver implements QueryInterface, AliasedInterface
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private CollectionArgumentProvider $collectionArgumentProvider,
    ) {
    }

    public function get(Argument $arguments): ?Author
    {
        return $this->authorRepository->find(id: $arguments['id']);
    }

    public function getCollection(Argument $arguments): array
    {
        [$limit, $orderBy, $criteria] = $this->collectionArgumentProvider->provide($arguments);

        $authors = $this->authorRepository->findBy(
            criteria: [],
            orderBy: [
                $orderBy => $criteria,
            ],
            limit: $limit,
            offset: 0
        );

        return [
            'authors' => $authors,
        ];
    }

    public static function getAliases(): array
    {
        return [
            'get' => 'getAuthor',
            'getCollection' => 'getAuthors',
        ];
    }
}
