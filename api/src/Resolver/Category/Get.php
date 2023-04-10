<?php

declare(strict_types=1);

namespace App\Resolver\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class Get implements QueryInterface, AliasedInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function __invoke(Argument $arguments): ?Category
    {
        return $this->categoryRepository->find(id: $arguments['id']);
    }

    public static function getAliases(): array
    {
        return [
            '__invoke' => 'getCategory',
        ];
    }
}
