<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211016155024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE size CHANGE stock_size_one stock_size_one INT DEFAULT NULL, CHANGE stock_size_two stock_size_two INT DEFAULT NULL, CHANGE stock_size_three stock_size_three INT DEFAULT NULL, CHANGE stock_size_four stock_size_four INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE size CHANGE stock_size_one stock_size_one INT NOT NULL, CHANGE stock_size_two stock_size_two INT NOT NULL, CHANGE stock_size_three stock_size_three INT NOT NULL, CHANGE stock_size_four stock_size_four INT NOT NULL');
    }
}
