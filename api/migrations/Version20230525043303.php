<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230525043303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added password field to user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP password');
    }
}
