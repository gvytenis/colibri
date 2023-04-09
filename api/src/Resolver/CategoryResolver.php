<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

readonly class CategoryResolver implements QueryInterface, AliasedInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function __invoke(Argument $args): ?Category
    {
        return $this->categoryRepository->find(id: $args['id']);
    }

    public static function getAliases(): array
    {
        return [
            'resolve' => 'Category',
        ];
    }
}
