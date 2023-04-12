<?php

declare(strict_types=1);

namespace App\Resolver\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class Get implements QueryInterface, AliasedInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(Argument $arguments): ?User
    {
        return $this->userRepository->find(id: $arguments['id']);
    }

    public static function getAliases(): array
    {
        return [
            '__invoke' => 'getUser',
        ];
    }
}
