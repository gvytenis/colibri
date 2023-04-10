<?php

declare(strict_types=1);

namespace App\Mutation\User;

use App\Entity\User;
use App\Service\MutationResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Validator\Exception\ArgumentsValidationException;
use Overblog\GraphQLBundle\Validator\InputValidator;
use Symfony\Component\Validator\ConstraintViolationList;

final readonly class Create implements MutationInterface, AliasedInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MutationResponseFactory $mutationResponseFactory
    ) {
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function __invoke(Argument $arguments, InputValidator $validator): array
    {
        $violations = $validator->validate(throw: false);
        assert($violations instanceof ConstraintViolationList);

        if ($violations->count()) {
            return $this->mutationResponseFactory
                ->violations($violations)
                ->getResponse();
        }

        $this->entityManager->persist($this->createUser(arguments: $arguments));
        $this->entityManager->flush();

        return $this->mutationResponseFactory
            ->success()
            ->getResponse();
    }

    public static function getAliases(): array
    {
        return [
            '__invoke' => 'createUser',
        ];
    }

    private function createUser(Argument $arguments): User
    {
        $input = $arguments->offsetGet('user');
        assert(is_array($input));

        return (new User())
            ->setName($input['name'])
            ->setUsername($input['username'])
            ->setEmail($input['email'])
            ->setStatus($input['status'])
            ->setRoles($input['roles']);
    }
}
