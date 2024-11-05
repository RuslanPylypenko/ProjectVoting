<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241103212405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cities (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, address_house_number VARCHAR(36) DEFAULT NULL, address_street VARCHAR(255) DEFAULT NULL, address_postal_code VARCHAR(16) DEFAULT NULL, address_city VARCHAR(255) NOT NULL, address_country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_votes (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_42DE7351166D1F9C (project_id), UNIQUE INDEX UNIQ_42DE7351A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, author_id INT NOT NULL, rejected_by_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, budget NUMERIC(10, 0) NOT NULL, status SMALLINT UNSIGNED NOT NULL, rejected_reason VARCHAR(255) DEFAULT NULL, rejected_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, address_house_number VARCHAR(36) DEFAULT NULL, address_street VARCHAR(255) DEFAULT NULL, address_postal_code VARCHAR(16) DEFAULT NULL, address_city VARCHAR(255) NOT NULL, address_country VARCHAR(255) NOT NULL, INDEX IDX_5C93B3A4613FECDF (session_id), INDEX IDX_5C93B3A4F675F31B (author_id), INDEX IDX_5C93B3A4CBF05FC9 (rejected_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_categories (project_entity_id INT NOT NULL, category_entity_id INT NOT NULL, INDEX IDX_8C4CD7929019388A (project_entity_id), INDEX IDX_8C4CD7924645AF6D (category_entity_id), PRIMARY KEY(project_entity_id, category_entity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_stages (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sessions (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9A609D138BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE submission_requirements (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, min_age INT NOT NULL, categories JSON DEFAULT NULL, max_budget VARCHAR(255) NOT NULL, min_budget VARCHAR(255) NOT NULL, only_residents TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D5B9CCF613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, password_hash VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, living_address_house_number VARCHAR(36) DEFAULT NULL, living_address_street VARCHAR(255) DEFAULT NULL, living_address_postal_code VARCHAR(16) DEFAULT NULL, living_address_city VARCHAR(255) NOT NULL, living_address_country VARCHAR(255) NOT NULL, registration_address_house_number VARCHAR(36) DEFAULT NULL, registration_address_street VARCHAR(255) DEFAULT NULL, registration_address_postal_code VARCHAR(16) DEFAULT NULL, registration_address_city VARCHAR(255) NOT NULL, registration_address_country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voting_requirements (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, min_age INT NOT NULL, max_votes INT NOT NULL, only_residents TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_BEE49869613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE winner_requirements (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, max_winners INT NOT NULL, min_votes INT NOT NULL, UNIQUE INDEX UNIQ_831BEE1A613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_votes ADD CONSTRAINT FK_42DE7351166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE project_votes ADD CONSTRAINT FK_42DE7351A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4CBF05FC9 FOREIGN KEY (rejected_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE projects_categories ADD CONSTRAINT FK_8C4CD7929019388A FOREIGN KEY (project_entity_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_categories ADD CONSTRAINT FK_8C4CD7924645AF6D FOREIGN KEY (category_entity_id) REFERENCES category_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sessions ADD CONSTRAINT FK_9A609D138BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id)');
        $this->addSql('ALTER TABLE submission_requirements ADD CONSTRAINT FK_8D5B9CCF613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id)');
        $this->addSql('ALTER TABLE voting_requirements ADD CONSTRAINT FK_BEE49869613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id)');
        $this->addSql('ALTER TABLE winner_requirements ADD CONSTRAINT FK_831BEE1A613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_votes DROP FOREIGN KEY FK_42DE7351166D1F9C');
        $this->addSql('ALTER TABLE project_votes DROP FOREIGN KEY FK_42DE7351A76ED395');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4613FECDF');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4F675F31B');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4CBF05FC9');
        $this->addSql('ALTER TABLE projects_categories DROP FOREIGN KEY FK_8C4CD7929019388A');
        $this->addSql('ALTER TABLE projects_categories DROP FOREIGN KEY FK_8C4CD7924645AF6D');
        $this->addSql('ALTER TABLE sessions DROP FOREIGN KEY FK_9A609D138BAC62AF');
        $this->addSql('ALTER TABLE submission_requirements DROP FOREIGN KEY FK_8D5B9CCF613FECDF');
        $this->addSql('ALTER TABLE voting_requirements DROP FOREIGN KEY FK_BEE49869613FECDF');
        $this->addSql('ALTER TABLE winner_requirements DROP FOREIGN KEY FK_831BEE1A613FECDF');
        $this->addSql('DROP TABLE category_entity');
        $this->addSql('DROP TABLE cities');
        $this->addSql('DROP TABLE project_votes');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE projects_categories');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE session_stages');
        $this->addSql('DROP TABLE sessions');
        $this->addSql('DROP TABLE submission_requirements');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE voting_requirements');
        $this->addSql('DROP TABLE winner_requirements');
    }
}
