<?php

declare(strict_types=1);

namespace App\Mutation\User;

use App\Repository\UserRepository;
use App\Service\MutationResponseFactory;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

final readonly class Delete implements MutationInterface, AliasedInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private MutationResponseFactory $mutationResponseFactory
    ) {
    }

    public function __invoke(Argument $arguments): array
    {
        $user = $this->userRepository->find(id: $arguments['id']);

        if ($user !== null) {
            $this->userRepository->remove(entity: $user, flush: true);
        }

        return $this->mutationResponseFactory
            ->success()
            ->getResponse();
    }

    public static function getAliases(): array
    {
        return [
            '__invoke' => 'deleteUser',
        ];
    }
}
