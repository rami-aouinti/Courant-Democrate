<?php

declare(strict_types=1);

namespace App\Project\Shared\SharedBoundedContext\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706094418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add shared users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE shared_users (id VARCHAR(36) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE shared_users');
    }
}
