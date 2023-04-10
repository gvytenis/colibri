<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class AuthorResolver implements QueryInterface, AliasedInterface
{
    public function __construct(
        private AuthorRepository $authorRepository,
    ) {
    }

    public function __invoke(Argument $arguments): ?Author
    {
        return $this->authorRepository->find(id: $arguments['id']);
    }

    public static function getAliases(): array
    {
        return [
            'resolve' => 'Author',
        ];
    }
}
