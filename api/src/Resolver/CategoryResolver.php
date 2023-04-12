<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CollectionArgumentProvider;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class CategoryResolver implements QueryInterface, AliasedInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private CollectionArgumentProvider $collectionArgumentProvider,
    ) {
    }

    public function get(Argument $arguments): ?Category
    {
        return $this->categoryRepository->find(id: $arguments['id']);
    }

    public function getCollection(Argument $arguments): array
    {
        [$limit, $orderBy, $criteria] = $this->collectionArgumentProvider->provide($arguments);

        $categories = $this->categoryRepository->findBy(
            criteria: [],
            orderBy: [
                $orderBy => $criteria,
            ],
            limit: $limit,
            offset: 0
        );

        return [
            'categories' => $categories,
        ];
    }

    public static function getAliases(): array
    {
        return [
            'get' => 'getCategory',
            'getCollection' => 'getCategories',
        ];
    }
}
