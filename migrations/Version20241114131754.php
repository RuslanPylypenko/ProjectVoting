<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114131754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_votes DROP INDEX UNIQ_42DE7351A76ED395, ADD INDEX IDX_42DE7351A76ED395 (user_id)');
        $this->addSql('ALTER TABLE project_votes DROP INDEX UNIQ_42DE7351166D1F9C, ADD INDEX IDX_42DE7351166D1F9C (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_votes DROP INDEX IDX_42DE7351166D1F9C, ADD UNIQUE INDEX UNIQ_42DE7351166D1F9C (project_id)');
        $this->addSql('ALTER TABLE project_votes DROP INDEX IDX_42DE7351A76ED395, ADD UNIQUE INDEX UNIQ_42DE7351A76ED395 (user_id)');
    }
}
