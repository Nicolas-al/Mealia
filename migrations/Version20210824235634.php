<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210824235634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avoir (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, INDEX IDX_659B1A43A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gift_card (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_E4696A8EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avoir ADD CONSTRAINT FK_659B1A43A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE gift_card ADD CONSTRAINT FK_E4696A8EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE avoir');
        $this->addSql('DROP TABLE gift_card');
    }
}
