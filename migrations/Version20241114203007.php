<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114203007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_history (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, initiator_id INT DEFAULT NULL, action SMALLINT UNSIGNED NOT NULL, field VARCHAR(255) DEFAULT NULL, old_value LONGTEXT DEFAULT NULL, new_value LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_B1A47C2E166D1F9C (project_id), INDEX IDX_B1A47C2E7DB3B714 (initiator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_history ADD CONSTRAINT FK_B1A47C2E166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE project_history ADD CONSTRAINT FK_B1A47C2E7DB3B714 FOREIGN KEY (initiator_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_history DROP FOREIGN KEY FK_B1A47C2E166D1F9C');
        $this->addSql('ALTER TABLE project_history DROP FOREIGN KEY FK_B1A47C2E7DB3B714');
        $this->addSql('DROP TABLE project_history');
    }
}
