<?php

declare(strict_types=1);

namespace App\Mutation;

use App\Manager\CategoryManager;
use App\Repository\CategoryRepository;
use App\Service\MutationResponseFactory;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Validator\Exception\ArgumentsValidationException;
use Overblog\GraphQLBundle\Validator\InputValidator;

class CategoryMutation extends AbstractBaseMutation implements MutationInterface, AliasedInterface
{
    public function __construct(
        private readonly MutationResponseFactory $mutationResponseFactory,
        private readonly CategoryManager $categoryManager,
        private readonly CategoryRepository $categoryRepository,
    ) {
        parent::__construct($this->mutationResponseFactory);
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function create(Argument $arguments, InputValidator $validator): array
    {
        $violations = $this->getViolations($validator);

        if ($violations->count()) {
            return $this->getViolationsResponse($violations);
        }

        $entity = $this->categoryManager->create(arguments: $arguments);
        $this->categoryRepository->save(entity: $entity, flush: true);

        return $this->getSuccessResponse();
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function update(Argument $arguments, InputValidator $validator): array
    {
        $entity = $this->categoryRepository->find(id: $arguments['id']);

        if ($entity === null) {
            return $this->getFailureResponse();
        }

        $violations = $this->getViolations($validator);

        if ($violations->count()) {
            return $this->getViolationsResponse($violations);
        }

        $entity = $this->categoryManager->update(arguments: $arguments, category: $entity);
        $this->categoryRepository->save(entity: $entity, flush: true);

        return $this->getSuccessResponse();
    }

    public function delete(Argument $arguments): array
    {
        $category = $this->categoryRepository->find(id: $arguments['id']);

        if ($category !== null) {
            $this->categoryRepository->remove(entity: $category, flush: true);
        }

        return $this->getSuccessResponse();
    }

    public static function getAliases(): array
    {
        return [
            'create' => 'createCategory',
            'update' => 'updateCategory',
            'delete' => 'deleteCategory',
        ];
    }
}
