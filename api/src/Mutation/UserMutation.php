<?php

declare(strict_types=1);

namespace App\Mutation;

use App\Manager\UserManager;
use App\Repository\UserRepository;
use App\Service\MutationResponseFactory;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Validator\Exception\ArgumentsValidationException;
use Overblog\GraphQLBundle\Validator\InputValidator;

class UserMutation extends AbstractBaseMutation implements MutationInterface, AliasedInterface
{
    public function __construct(
        private readonly MutationResponseFactory $mutationResponseFactory,
        private readonly UserManager $userManager,
        private readonly UserRepository $userRepository,
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

        $entity = $this->userManager->create(arguments: $arguments);
        $this->userRepository->save(entity: $entity, flush: true);

        return $this->getSuccessResponse();
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function update(Argument $arguments, InputValidator $validator): array
    {
        $entity = $this->userRepository->find(id: $arguments['id']);

        if ($entity === null) {
            return $this->getFailureResponse();
        }

        $violations = $this->getViolations($validator);

        if ($violations->count()) {
            return $this->getViolationsResponse($violations);
        }

        $entity = $this->userManager->update(arguments: $arguments, user: $entity);
        $this->userRepository->save(entity: $entity, flush: true);

        return $this->getSuccessResponse();
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function changePassword(Argument $arguments, InputValidator $validator): array
    {
        $entity = $this->userRepository->find(id: $arguments['id']);

        if ($entity === null) {
            return $this->getFailureResponse();
        }

        $violations = $this->getViolations($validator);

        if ($violations->count()) {
            return $this->getViolationsResponse($violations);
        }

        $entity = $this->userManager->changePassword(arguments: $arguments, user: $entity);
        $this->userRepository->save(entity: $entity, flush: true);

        return $this->getSuccessResponse();
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function updateAccount(Argument $arguments, InputValidator $validator): array
    {
        $entity = $this->userRepository->find(id: $arguments['id']);

        if ($entity === null) {
            return $this->getFailureResponse();
        }

        $violations = $this->getViolations($validator);

        if ($violations->count()) {
            return $this->getViolationsResponse($violations);
        }

        $entity = $this->userManager->updateAccount(arguments: $arguments, user: $entity);
        $this->userRepository->save(entity: $entity, flush: true);

        return $this->getSuccessResponse();
    }

    public function delete(Argument $arguments): array
    {
        $entity = $this->userRepository->find(id: $arguments['id']);

        if ($entity !== null) {
            $this->userRepository->remove(entity: $entity, flush: true);
        }

        return $this->getSuccessResponse();
    }

    public static function getAliases(): array
    {
        return [
            'create' => 'createUser',
            'update' => 'updateUser',
            'delete' => 'deleteUser',
            'changePassword' => 'changePassword',
            'updateAccount' => 'updateAccount',
        ];
    }
}
