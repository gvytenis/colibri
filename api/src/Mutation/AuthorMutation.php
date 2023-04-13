<?php

declare(strict_types=1);

namespace App\Mutation;

use App\Manager\AuthorManager;
use App\Repository\AuthorRepository;
use App\Service\MutationResponseFactory;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Validator\Exception\ArgumentsValidationException;
use Overblog\GraphQLBundle\Validator\InputValidator;

final class AuthorMutation extends AbstractBaseMutation implements MutationInterface, AliasedInterface
{
    public function __construct(
        private readonly MutationResponseFactory $mutationResponseFactory,
        private readonly AuthorManager $authorManager,
        private readonly AuthorRepository $authorRepository
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

        $entity = $this->authorManager->create(arguments: $arguments);
        $this->authorRepository->save(entity: $entity, flush: true);

        return $this->getSuccessResponse();
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function update(Argument $arguments, InputValidator $validator): array
    {
        $entity = $this->authorRepository->find(id: $arguments['id']);

        if ($entity === null) {
            return $this->getFailureResponse();
        }

        $violations = $this->getViolations($validator);

        if ($violations->count()) {
            return $this->getViolationsResponse($violations);
        }

        $entity = $this->authorManager->update(arguments: $arguments, author: $entity);
        $this->authorRepository->save(entity: $entity, flush: true);

        return $this->getSuccessResponse();
    }

    public function delete(Argument $arguments): array
    {
        $author = $this->authorRepository->find(id: $arguments['id']);

        if ($author !== null) {
            $this->authorRepository->remove(entity: $author, flush: true);
        }

        return $this->getSuccessResponse();
    }

    public static function getAliases(): array
    {
        return [
            'create' => 'createAuthor',
            'update' => 'updateAuthor',
            'delete' => 'deleteAuthor',
        ];
    }
}
