<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251002194307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {   
        $this->addSql("ALTER TABLE users ADD username VARCHAR(255) DEFAULT NULL");
        $this->addSql("UPDATE users SET username = email WHERE username IS NULL");
        $this->addSql("ALTER TABLE users ALTER COLUMN username SET NOT NULL");
        $this->addSql("TRUNCATE TABLE users RESTART IDENTITY CASCADE");
        $this->addSql("TRUNCATE TABLE student RESTART IDENTITY CASCADE");
        // this up() migration is auto-generated, please modify it to your needs
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP username');
    }
}
