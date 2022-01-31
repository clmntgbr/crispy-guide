<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129184300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE gas_price DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE gas_service DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE gas_station DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE gas_station_status DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE gas_station_status_history DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE gas_type DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE google_place DROP created_by, DROP updated_by');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ADD created_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD updated_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE gas_price ADD created_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD updated_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE gas_service ADD created_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD updated_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE gas_station ADD created_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD updated_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE gas_station_status ADD created_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD updated_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE gas_station_status_history ADD created_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD updated_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE gas_type ADD created_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD updated_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE google_place ADD created_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD updated_by VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
    }
}
