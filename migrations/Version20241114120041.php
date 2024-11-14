<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114120041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects CHANGE session_id session_id INT DEFAULT NULL, CHANGE author_id author_id INT DEFAULT NULL, CHANGE category category VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sessions CHANGE city_id city_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sessions CHANGE city_id city_id INT NOT NULL');
        $this->addSql('ALTER TABLE projects CHANGE session_id session_id INT NOT NULL, CHANGE author_id author_id INT NOT NULL, CHANGE category category SMALLINT UNSIGNED NOT NULL');
    }
}
