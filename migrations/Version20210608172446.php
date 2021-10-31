<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608172446 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD delivery_id INT DEFAULT NULL, DROP delivery');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939812136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F529939812136921 ON `order` (delivery_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939812136921');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP INDEX UNIQ_F529939812136921 ON `order`');
        $this->addSql('ALTER TABLE `order` ADD delivery VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP delivery_id');
    }
}
