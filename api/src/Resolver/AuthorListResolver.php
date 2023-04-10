<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Repository\AuthorRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class AuthorListResolver implements QueryInterface, AliasedInterface
{
    public function __construct(
        private AuthorRepository $authorRepository,
    ) {
    }

    public function __invoke(Argument $arguments): array
    {
        $limit = $arguments['limit'] ?? 10;
        $orderBy = $arguments['orderBy'] ?? 'id';
        $criteria = $arguments['criteria'] ?? 'desc';

        $authors = $this->authorRepository->findBy(
            criteria: [],
            orderBy: [
                $orderBy => $criteria,
            ],
            limit: $limit,
            offset: 0
        );

        return [
            'authors' => $authors,
        ];
    }

    public static function getAliases(): array
    {
        return [
            'resolve' => 'AuthorList',
        ];
    }
}
