<?php

declare(strict_types=1);

namespace App\Mutation;

use App\Manager\ReservationManager;
use App\Repository\ReservationRepository;
use App\Service\MutationResponseFactory;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Validator\Exception\ArgumentsValidationException;
use Overblog\GraphQLBundle\Validator\InputValidator;

class ReservationMutation extends AbstractBaseMutation implements MutationInterface, AliasedInterface
{
    public function __construct(
        private readonly MutationResponseFactory $mutationResponseFactory,
        private readonly ReservationManager $reservationManager,
        private readonly ReservationRepository $reservationRepository,
    ) {
        parent::__construct($this->mutationResponseFactory);
    }

    /**
     * @throws ArgumentsValidationException
     */
    public function create(Argument $arguments, InputValidator $validator): array
    {
        $violations = $this->getViolations($validator);

        if ($violations->count()) {
            return $this->getViolationsResponse($violations);
        }

        $entity = $this->reservationManager->create(arguments: $arguments);
        $this->reservationRepository->save(entity: $entity, flush: true);

        return $this->getSuccessResponse();
    }

    public function update(Argument $arguments, InputValidator $validator): array
    {
        return [];
    }

    public function delete(Argument $arguments): array
    {
        return [];
    }

    public static function getAliases(): array
    {
        return [
            'create' => 'createReservation',
            'update' => 'updateReservation',
            'delete' => 'deleteReservation',
        ];
    }
}
