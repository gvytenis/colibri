<?php

declare(strict_types=1);

namespace App\Tests;

use App\Fixture\AuthorFixture;
use App\Fixture\BookFixture;
use App\Fixture\CategoryFixture;
use App\Fixture\ReservationFixture;
use App\Fixture\UserFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait FixtureTrait
{
    public function recreateDatabase(): static
    {
        assert($this->entityManager instanceof EntityManagerInterface);

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->entityManager->getConnection()->setAutoCommit(false);
        $this->entityManager->beginTransaction();

        return $this;
    }

    public function loadFixtures(): static
    {
        assert($this->entityManager instanceof EntityManagerInterface);

        $purger = new ORMPurger($this->entityManager);

        $executor = new ORMExecutor($this->entityManager);
        $executor->setPurger($purger);
        $executor->execute($this->getFixtureInstances());

        return $this;
    }

    public function cleanupAfterTest(): static
    {
        assert($this->entityManager instanceof EntityManagerInterface);

        $this->entityManager->rollback();
        $this->entityManager->close();

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();

        return $this;
    }

    /** @return FixtureInterface[] */
    public function getFixtureInstances(): array
    {
        assert($this->userPasswordHasher instanceof UserPasswordHasherInterface);

        return [
            new AuthorFixture(),
            new CategoryFixture(),
            new BookFixture(),
            new UserFixture($this->userPasswordHasher),
            new ReservationFixture(),
        ];
    }
}
