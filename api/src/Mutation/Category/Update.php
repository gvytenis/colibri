<?php

declare(strict_types=1);

namespace App\Mutation\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\MutationResponseFactory;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Validator\Exception\ArgumentsValidationException;
use Overblog\GraphQLBundle\Validator\InputValidator;
use Symfony\Component\Validator\ConstraintViolationList;

final readonly class Update implements MutationInterface, AliasedInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private MutationResponseFactory $mutationResponseFactory
    ) {
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function __invoke(Argument $arguments, InputValidator $validator): array
    {
        $category = $this->categoryRepository->find(id: $arguments['id']);

        if ($category === null) {
            return $this->mutationResponseFactory
                ->failure()
                ->getResponse();
        }

        $violations = $validator->validate(throw: false);
        assert($violations instanceof ConstraintViolationList);

        if ($violations->count()) {
            return $this->mutationResponseFactory
                ->violations($violations)
                ->getResponse();
        }

        $category = $this->updateCategory($category, $arguments);
        $this->categoryRepository->save(entity: $category, flush: true);

        return $this->mutationResponseFactory
            ->success()
            ->getResponse();
    }

    public static function getAliases(): array
    {
        return [
            '__invoke' => 'updateCategory',
        ];
    }

    private function updateCategory(Category $category, Argument $arguments): Category
    {
        $input = $arguments->offsetGet('category');
        assert(is_array($input));

        return $category->setName($input['name']);
    }
}
