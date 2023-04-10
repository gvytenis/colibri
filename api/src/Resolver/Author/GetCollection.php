<?php

declare(strict_types=1);

namespace App\Resolver\Author;

use App\Repository\AuthorRepository;
use App\Service\CollectionArgumentProvider;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class GetCollection implements QueryInterface, AliasedInterface
{
    public function __construct(
        private CollectionArgumentProvider $collectionArgumentProvider,
        private AuthorRepository $authorRepository,
    ) {
    }

    public function __invoke(Argument $arguments): array
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
            '__invoke' => 'getAuthors',
        ];
    }
}
