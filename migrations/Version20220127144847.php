<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127144847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gas_price ADD currency_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE gas_price ADD CONSTRAINT FK_EEF8FDB638248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('CREATE INDEX IDX_EEF8FDB638248176 ON gas_price (currency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gas_price DROP FOREIGN KEY FK_EEF8FDB638248176');
        $this->addSql('DROP INDEX IDX_EEF8FDB638248176 ON gas_price');
        $this->addSql('ALTER TABLE gas_price DROP currency_id');
    }
}
