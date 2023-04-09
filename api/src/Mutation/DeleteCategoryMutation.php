<?php

declare(strict_types=1);

namespace App\Mutation;

use App\Repository\CategoryRepository;
use App\Service\MutationResponseFactory;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

final readonly class DeleteCategoryMutation implements MutationInterface, AliasedInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private MutationResponseFactory $mutationResponseFactory
    ) {
    }

    public function __invoke(Argument $arguments): array
    {
        $category = $this->categoryRepository->find(id: $arguments['id']);

        if ($category !== null) {
            $this->categoryRepository->remove(entity: $category, flush: true);
        }

        return $this->mutationResponseFactory
            ->success()
            ->getResponse();
    }

    public static function getAliases(): array
    {
        return [
            'resolve' => 'DeleteCategory',
        ];
    }
}
