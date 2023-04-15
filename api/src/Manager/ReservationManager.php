<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Reservation;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Overblog\GraphQLBundle\Definition\Argument;

final class ReservationManager
{
    public function __construct(
        private BookRepository $bookRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function create(Argument $arguments): Reservation
    {
        return $this->createOrUpdate($arguments);
    }

    public function update(Argument $arguments, Reservation $reservation): Reservation
    {
        return $this->createOrUpdate($arguments, $reservation);
    }

    private function createOrUpdate(Argument $arguments, ?Reservation $reservation = null): Reservation
    {
        $input = $arguments->offsetGet('reservation');
        assert(is_array($input));

        return ($reservation ?? new Reservation())
            ->setBook($this->bookRepository->find(id: $input['bookId']))
            ->setUser($this->userRepository->find(id: $input['userId']))
            ->setDateFrom(new DateTimeImmutable($input['dateFrom']))
            ->setDateTo(new DateTimeImmutable($input['dateTo']))
            ->setCreatedAt($reservation ? $reservation->getCreatedAt() : new DateTimeImmutable())
            ->setUpdatedAt($reservation ? $reservation->getUpdatedAt() : new DateTimeImmutable());
    }
}
