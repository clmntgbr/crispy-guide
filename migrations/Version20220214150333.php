<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214150333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT UNSIGNED AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, size NUMERIC(10, 0) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gas_station ADD preview_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE gas_station ADD CONSTRAINT FK_6B3064ACCDE46FDB FOREIGN KEY (preview_id) REFERENCES media (id)');
        $this->addSql('CREATE INDEX IDX_6B3064ACCDE46FDB ON gas_station (preview_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gas_station DROP FOREIGN KEY FK_6B3064ACCDE46FDB');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP INDEX IDX_6B3064ACCDE46FDB ON gas_station');
        $this->addSql('ALTER TABLE gas_station DROP preview_id');
    }
}
