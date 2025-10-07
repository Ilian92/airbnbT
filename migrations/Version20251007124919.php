<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251007124919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'modification de la colonne note de int a string';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE property ALTER note TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE property ALTER note TYPE INT');
    }
}
