<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128144811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE google_place (id INT UNSIGNED AUTO_INCREMENT NOT NULL, google_id VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, place_id VARCHAR(255) DEFAULT NULL, compound_code VARCHAR(255) DEFAULT NULL, global_code VARCHAR(255) DEFAULT NULL, google_rating VARCHAR(255) DEFAULT NULL, rating VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, user_ratings_total VARCHAR(255) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, business_status VARCHAR(255) DEFAULT NULL, opening_hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_EDF05AC2DA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gas_station ADD google_place_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE gas_station ADD CONSTRAINT FK_6B3064AC983C031 FOREIGN KEY (google_place_id) REFERENCES google_place (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B3064AC983C031 ON gas_station (google_place_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gas_station DROP FOREIGN KEY FK_6B3064AC983C031');
        $this->addSql('DROP TABLE google_place');
        $this->addSql('DROP INDEX UNIQ_6B3064AC983C031 ON gas_station');
        $this->addSql('ALTER TABLE gas_station DROP google_place_id');
    }
}
