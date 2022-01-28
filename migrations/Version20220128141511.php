<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128141511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gas_station_status (id INT UNSIGNED AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_DC5BBE4DAEA34913 (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gas_station_status_history (id INT UNSIGNED AUTO_INCREMENT NOT NULL, gas_station_id INT UNSIGNED NOT NULL, gas_station_status_id INT UNSIGNED NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BB6C189B916BFF50 (gas_station_id), INDEX IDX_BB6C189B98FCD035 (gas_station_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gas_station_status_history ADD CONSTRAINT FK_BB6C189B916BFF50 FOREIGN KEY (gas_station_id) REFERENCES gas_station (id)');
        $this->addSql('ALTER TABLE gas_station_status_history ADD CONSTRAINT FK_BB6C189B98FCD035 FOREIGN KEY (gas_station_status_id) REFERENCES gas_station_status (id)');
        $this->addSql('ALTER TABLE gas_station ADD gas_station_status_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE gas_station ADD CONSTRAINT FK_6B3064AC98FCD035 FOREIGN KEY (gas_station_status_id) REFERENCES gas_station_status (id)');
        $this->addSql('CREATE INDEX IDX_6B3064AC98FCD035 ON gas_station (gas_station_status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gas_station DROP FOREIGN KEY FK_6B3064AC98FCD035');
        $this->addSql('ALTER TABLE gas_station_status_history DROP FOREIGN KEY FK_BB6C189B98FCD035');
        $this->addSql('DROP TABLE gas_station_status');
        $this->addSql('DROP TABLE gas_station_status_history');
        $this->addSql('DROP INDEX IDX_6B3064AC98FCD035 ON gas_station');
        $this->addSql('ALTER TABLE gas_station DROP gas_station_status_id');
    }
}
