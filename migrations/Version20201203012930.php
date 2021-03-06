<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203012930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress ADD city VARCHAR(255) NOT NULL, CHANGE street_adress street VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD adress_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498486F9AC ON user (adress_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress ADD street_adress VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP street, DROP city');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6498486F9AC');
        $this->addSql('DROP INDEX UNIQ_8D93D6498486F9AC ON `user`');
        $this->addSql('ALTER TABLE `user` DROP adress_id');
    }
}
