<?php

declare(strict_types=1);

namespace App\Mutation\User;

use App\Entity\User;
use App\Repository\UserRepository;
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
        private UserRepository $userRepository,
        private MutationResponseFactory $mutationResponseFactory
    ) {
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function __invoke(Argument $arguments, InputValidator $validator): array
    {
        $user = $this->userRepository->find(id: $arguments['id']);

        if ($user === null) {
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

        $user = $this->updateUser($user, $arguments);
        $this->userRepository->save(entity: $user, flush: true);

        return $this->mutationResponseFactory
            ->success()
            ->getResponse();
    }

    public static function getAliases(): array
    {
        return [
            '__invoke' => 'updateUser',
        ];
    }

    private function updateUser(User $user, Argument $arguments): User
    {
        $input = $arguments->offsetGet('user');
        assert(is_array($input));

        return $user->setName($input['name'])
            ->setUsername($input['username'])
            ->setEmail($input['email'])
            ->setStatus($input['status'])
            ->setRoles($input['roles']);
    }
}
