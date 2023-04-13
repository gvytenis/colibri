<?php

declare(strict_types=1);

namespace App\Mutation;

use App\Service\MutationResponseFactory;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Validator\Exception\ArgumentsValidationException;
use Overblog\GraphQLBundle\Validator\InputValidator;
use Symfony\Component\Validator\ConstraintViolationList;

abstract class AbstractBaseMutation
{
    public function __construct(
        private readonly MutationResponseFactory $mutationResponseFactory
    ) {
    }

    abstract public function create(Argument $arguments, InputValidator $validator): array;

    abstract public function update(Argument $arguments, InputValidator $validator): array;

    abstract public function delete(Argument $arguments): array;

    /**
     * @throws ArgumentsValidationException
     */
    public function getViolations(InputValidator $validator): ConstraintViolationList
    {
        $violations = $validator->validate(throw: false);
        assert($violations instanceof ConstraintViolationList);

        return $violations;
    }

    public function getViolationsResponse(ConstraintViolationList $violations): array
    {
        return $this->mutationResponseFactory
            ->violations($violations)
            ->getResponse();
    }

    public function getSuccessResponse(): array
    {
        return $this->mutationResponseFactory
            ->success()
            ->getResponse();
    }

    public function getFailureResponse(): array
    {
        return $this->mutationResponseFactory
            ->failure()
            ->getResponse();
    }
}
