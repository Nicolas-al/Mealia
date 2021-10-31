<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210823012146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1C54C8C93 ON category (type_id)');
        $this->addSql('ALTER TABLE product_collection ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_collection ADD CONSTRAINT FK_6F2A3A19C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_6F2A3A19C54C8C93 ON product_collection (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1C54C8C93');
        $this->addSql('DROP INDEX IDX_64C19C1C54C8C93 ON category');
        $this->addSql('ALTER TABLE category DROP type_id');
        $this->addSql('ALTER TABLE product_collection DROP FOREIGN KEY FK_6F2A3A19C54C8C93');
        $this->addSql('DROP INDEX IDX_6F2A3A19C54C8C93 ON product_collection');
        $this->addSql('ALTER TABLE product_collection DROP type_id');
    }
}
