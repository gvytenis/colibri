<?php

declare(strict_types=1);

namespace App\Resolver\Category;

use App\Repository\CategoryRepository;
use App\Service\CollectionArgumentProvider;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class GetCollection implements QueryInterface, AliasedInterface
{
    public function __construct(
        private CollectionArgumentProvider $collectionArgumentProvider,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function __invoke(Argument $arguments): array
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
            '__invoke' => 'getCategories',
        ];
    }
}
