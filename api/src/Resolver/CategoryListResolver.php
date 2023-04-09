<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Repository\CategoryRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

readonly class CategoryListResolver implements QueryInterface, AliasedInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function __invoke(Argument $args): array
    {
        $limit = $args['limit'] ?? 10;
        $orderBy = $args['orderBy'] ?? 'id';
        $criteria = $args['criteria'] ?? 'desc';

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
            'resolve' => 'CategoryList',
        ];
    }
}
