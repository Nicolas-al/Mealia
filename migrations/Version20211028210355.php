<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211028210355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products_ordered ADD price_size_one INT DEFAULT NULL, ADD price_size_two INT DEFAULT NULL, ADD price_size_three INT DEFAULT NULL, ADD size_one VARCHAR(255) DEFAULT NULL, ADD size_two VARCHAR(255) DEFAULT NULL, ADD size_three VARCHAR(255) DEFAULT NULL, DROP price');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products_ordered ADD price INT NOT NULL, DROP price_size_one, DROP price_size_two, DROP price_size_three, DROP size_one, DROP size_two, DROP size_three');
    }
}
