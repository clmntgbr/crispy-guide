<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220125200009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT UNSIGNED AUTO_INCREMENT NOT NULL, vicinity VARCHAR(255) DEFAULT NULL, street VARCHAR(255) NOT NULL, number VARCHAR(255) DEFAULT NULL, city VARCHAR(255) NOT NULL, region VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, longitude VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gas_price (id INT UNSIGNED AUTO_INCREMENT NOT NULL, gas_type_id INT UNSIGNED NOT NULL, gas_station_id INT UNSIGNED NOT NULL, value INT NOT NULL, date DATETIME NOT NULL, date_timestamp INT NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EEF8FDB63145108E (gas_type_id), INDEX IDX_EEF8FDB6916BFF50 (gas_station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gas_service (id INT UNSIGNED AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_159406CFAEA34913 (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gas_stations_services (gas_service_id INT UNSIGNED NOT NULL, gas_station_id INT UNSIGNED NOT NULL, INDEX IDX_FB9897DF5D8AE483 (gas_service_id), INDEX IDX_FB9897DF916BFF50 (gas_station_id), PRIMARY KEY(gas_service_id, gas_station_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gas_station (id INT UNSIGNED NOT NULL, address_id INT UNSIGNED DEFAULT NULL, pop VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, element LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', is_closed TINYINT(1) DEFAULT \'0\' NOT NULL, closed_at DATETIME DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6B3064ACF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gas_type (id INT UNSIGNED NOT NULL, reference VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8A29EDAAEA34913 (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gas_price ADD CONSTRAINT FK_EEF8FDB63145108E FOREIGN KEY (gas_type_id) REFERENCES gas_type (id)');
        $this->addSql('ALTER TABLE gas_price ADD CONSTRAINT FK_EEF8FDB6916BFF50 FOREIGN KEY (gas_station_id) REFERENCES gas_station (id)');
        $this->addSql('ALTER TABLE gas_stations_services ADD CONSTRAINT FK_FB9897DF5D8AE483 FOREIGN KEY (gas_service_id) REFERENCES gas_service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gas_stations_services ADD CONSTRAINT FK_FB9897DF916BFF50 FOREIGN KEY (gas_station_id) REFERENCES gas_station (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gas_station ADD CONSTRAINT FK_6B3064ACF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gas_station DROP FOREIGN KEY FK_6B3064ACF5B7AF75');
        $this->addSql('ALTER TABLE gas_stations_services DROP FOREIGN KEY FK_FB9897DF5D8AE483');
        $this->addSql('ALTER TABLE gas_price DROP FOREIGN KEY FK_EEF8FDB6916BFF50');
        $this->addSql('ALTER TABLE gas_stations_services DROP FOREIGN KEY FK_FB9897DF916BFF50');
        $this->addSql('ALTER TABLE gas_price DROP FOREIGN KEY FK_EEF8FDB63145108E');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE gas_price');
        $this->addSql('DROP TABLE gas_service');
        $this->addSql('DROP TABLE gas_stations_services');
        $this->addSql('DROP TABLE gas_station');
        $this->addSql('DROP TABLE gas_type');
    }
}
