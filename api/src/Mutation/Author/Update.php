<?php

declare(strict_types=1);

namespace App\Mutation\Author;

use App\Entity\Author;
use App\Repository\AuthorRepository;
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
        private AuthorRepository $authorRepository,
        private MutationResponseFactory $mutationResponseFactory
    ) {
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function __invoke(Argument $arguments, InputValidator $validator): array
    {
        $author = $this->authorRepository->find(id: $arguments['id']);

        if ($author === null) {
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

        $author = $this->updateAuthor($author, $arguments);
        $this->authorRepository->save(entity: $author, flush: true);

        return $this->mutationResponseFactory
            ->success()
            ->getResponse();
    }

    public static function getAliases(): array
    {
        return [
            '__invoke' => 'updateAuthor',
        ];
    }

    private function updateAuthor(Author $author, Argument $arguments): Author
    {
        $input = $arguments->offsetGet('author');
        assert(is_array($input));

        return $author->setName($input['name']);
    }
}
