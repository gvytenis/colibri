<?php

declare(strict_types=1);

namespace App\Fixture;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Entity\User;
use Carbon\Carbon;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var Book $book1 */
        $book1 = $this->getReference(BookFixture::REFERENCE_BOOK_1);

        /** @var Book $book2 */
        $book2 = $this->getReference(BookFixture::REFERENCE_BOOK_2);

        /** @var User $user1 */
        $user1 = $this->getReference(UserFixture::REFERENCE_USER_1);

        /** @var User $user2 */
        $user2 = $this->getReference(UserFixture::REFERENCE_USER_2);

        $reservation1 = (new Reservation())
            ->setBook($book1)
            ->setUser($user1)
            ->setDateFrom($this->createDate(year: 2023, month: 5, day: 12, hour: 13))
            ->setDateTo($this->createDate(year: 2023, month: 5, day: 24, hour: 17))
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $reservation2 = (new Reservation())
            ->setBook($book2)
            ->setUser($user2)
            ->setDateFrom($this->createDate(year: 2023, month: 5, day: 17, hour: 9))
            ->setDateTo($this->createDate(year: 2023, month: 5, day: 24, hour: 17))
            ->setCreatedAt(Carbon::now())
            ->setUpdatedAt(Carbon::now());

        $manager->persist($reservation1);
        $manager->persist($reservation2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BookFixture::class,
            UserFixture::class,
        ];
    }

    public function createDate(int $year, int $month, int $day, int $hour, int $minute = 0): DateTimeInterface
    {
        $date = Carbon::create($year, $month, $day, $hour, $minute);
        assert($date instanceof DateTimeInterface);

        return $date;
    }
}
