<?php

declare(strict_types=1);

namespace App\Tests;

use App\Fixture\AuthorFixture;
use App\Fixture\BookFixture;
use App\Fixture\CategoryFixture;
use App\Fixture\UserFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

trait FixtureTrait
{
    public function recreateDatabase(EntityManagerInterface $entityManager): static
    {
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $entityManager->getConnection()->setAutoCommit(false);
        $entityManager->beginTransaction();

        return $this;
    }

    public function loadFixtures(EntityManagerInterface $entityManager): static
    {
        $purger = new ORMPurger($entityManager);

        $executor = new ORMExecutor($entityManager);
        $executor->setPurger($purger);
        $executor->execute($this->getFixtureInstances());

        return $this;
    }

    public function cleanupAfterTest(EntityManagerInterface $entityManager): static
    {
        $entityManager->rollback();
        $entityManager->close();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();

        return $this;
    }

    /** @return FixtureInterface[] */
    public function getFixtureInstances(): array
    {
        return [
            new AuthorFixture(),
            new CategoryFixture(),
            new BookFixture(),
            new UserFixture(),
        ];
    }
}
