<?php

declare(strict_types=1);

namespace App\Mutation\Author;

use App\Repository\AuthorRepository;
use App\Service\MutationResponseFactory;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

final readonly class Delete implements MutationInterface, AliasedInterface
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private MutationResponseFactory $mutationResponseFactory
    ) {
    }

    public function __invoke(Argument $arguments): array
    {
        $author = $this->authorRepository->find(id: $arguments['id']);

        if ($author !== null) {
            $this->authorRepository->remove(entity: $author, flush: true);
        }

        return $this->mutationResponseFactory
            ->success()
            ->getResponse();
    }

    public static function getAliases(): array
    {
        return [
            '__invoke' => 'deleteAuthor',
        ];
    }
}