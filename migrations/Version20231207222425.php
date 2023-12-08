<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207222425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `option` CHANGE label label VARCHAR(191) NOT NULL, CHANGE name name VARCHAR(191) NOT NULL, CHANGE value value VARCHAR(191) DEFAULT NULL, CHANGE type type VARCHAR(191) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8600B05E237E06 ON `option` (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5A8600B05E237E06 ON `option`');
        $this->addSql('ALTER TABLE `option` CHANGE label label VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE value value VARCHAR(255) DEFAULT NULL, CHANGE type type VARCHAR(255) NOT NULL');
    }
}
