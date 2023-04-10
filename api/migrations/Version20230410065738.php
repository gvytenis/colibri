<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230410065738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added createdAt and updatedAt to author and category tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE author ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE category ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE author DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE category DROP created_at, DROP updated_at');
    }
}
