<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\CollectionArgumentProvider;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class UserResolver implements QueryInterface, AliasedInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private CollectionArgumentProvider $collectionArgumentProvider,
    ) {
    }

    public function get(Argument $arguments): ?User
    {
        return $this->userRepository->find(id: $arguments['id']);
    }

    public function getCollection(Argument $arguments): array
    {
        [$limit, $orderBy, $criteria] = $this->collectionArgumentProvider->provide($arguments);

        $users = $this->userRepository->findBy(
            criteria: [],
            orderBy: [
                $orderBy => $criteria,
            ],
            limit: $limit,
            offset: 0
        );

        return [
            'users' => $users,
        ];
    }

    public static function getAliases(): array
    {
        return [
            'get' => 'getUser',
            'getCollection' => 'getUsers',
        ];
    }
}
