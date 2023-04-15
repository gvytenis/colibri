<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\CollectionArgumentProvider;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

final readonly class ReservationResolver implements QueryInterface, AliasedInterface
{
    public function __construct(
        private ReservationRepository $reservationRepository,
        private CollectionArgumentProvider $collectionArgumentProvider,
    ) {
    }

    public function get(Argument $arguments): ?Reservation
    {
        return $this->reservationRepository->find(id: $arguments['id']);
    }

    public function getCollection(Argument $arguments): array
    {
        [$limit, $orderBy, $criteria] = $this->collectionArgumentProvider->provide($arguments);

        $reservations = $this->reservationRepository->findBy(
            criteria: [],
            orderBy: [
                $orderBy => $criteria,
            ],
            limit: $limit,
            offset: 0
        );

        return [
            'reservations' => $reservations,
        ];
    }

    public static function getAliases(): array
    {
        return [
            'get' => 'getReservation',
            'getCollection' => 'getReservations',
        ];
    }
}
