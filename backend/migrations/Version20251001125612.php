<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251001125612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial tables exam and student';
    }

    public function up(Schema $schema): void
    {
        // Création de la table student
        $this->addSql('CREATE TABLE IF NOT EXISTS student (
            id SERIAL NOT NULL,
            name VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');

        // Création de la table exam
        $this->addSql('CREATE TABLE IF NOT EXISTS exam (
            id SERIAL NOT NULL,
            student_id INT NOT NULL,
            location VARCHAR(255) DEFAULT NULL,
            date DATE NOT NULL,
            time TIME(0) WITHOUT TIME ZONE NOT NULL,
            status VARCHAR(50) NOT NULL,
            PRIMARY KEY(id)
        )');

        // Index et contrainte FK
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_38BBA6C6CB944F1A ON exam (student_id)');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('COMMENT ON COLUMN exam.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN exam.time IS \'(DC2Type:time_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS exam');
        $this->addSql('DROP TABLE IF EXISTS student');
    }
}
