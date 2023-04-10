<?php

declare(strict_types=1);

namespace App\Mutation;

use App\Entity\Author;
use App\Service\MutationResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Validator\Exception\ArgumentsValidationException;
use Overblog\GraphQLBundle\Validator\InputValidator;
use Symfony\Component\Validator\ConstraintViolationList;

final readonly class CreateAuthorMutation implements MutationInterface, AliasedInterface
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

        $this->entityManager->persist($this->createAuthor(arguments: $arguments));
        $this->entityManager->flush();

        return $this->mutationResponseFactory
            ->success()
            ->getResponse();
    }

    public static function getAliases(): array
    {
        return [
            'resolve' => 'CreateCategory',
        ];
    }

    private function createAuthor(Argument $arguments): Author
    {
        $input = $arguments->offsetGet('author');
        assert(is_array($input));

        return (new Author())
            ->setName($input['name']);
    }
}
