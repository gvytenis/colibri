<?php

declare(strict_types=1);

namespace App\Mutation;

use App\Manager\BookManager;
use App\Repository\BookRepository;
use App\Service\MutationResponseFactory;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Validator\Exception\ArgumentsValidationException;
use Overblog\GraphQLBundle\Validator\InputValidator;

class BookMutation extends AbstractBaseMutation implements MutationInterface, AliasedInterface
{
    public function __construct(
        private readonly MutationResponseFactory $mutationResponseFactory,
        private readonly BookManager $bookManager,
        private readonly BookRepository $bookRepository
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

        $entity = $this->bookManager->create(arguments: $arguments);
        $this->bookRepository->save(entity: $entity, flush: true);

        return $this->getSuccessResponse();
    }

    public function update(Argument $arguments, InputValidator $validator): array
    {
        return $this->getSuccessResponse();
    }

    public function delete(Argument $arguments): array
    {
        return $this->getSuccessResponse();
    }

    public static function getAliases(): array
    {
        return [
            'create' => 'createBook',
            'update' => 'updateBook',
            'delete' => 'deleteBook',
        ];
    }
}
