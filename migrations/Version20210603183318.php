<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210603183318 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD614C7E7');
        $this->addSql('DROP TABLE price');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA7456F54');
        $this->addSql('DROP INDEX UNIQ_D34A04ADD614C7E7 ON product');
        $this->addSql('DROP INDEX UNIQ_D34A04ADA7456F54 ON product');
        $this->addSql('ALTER TABLE product ADD text LONGTEXT DEFAULT NULL, ADD online TINYINT(1) NOT NULL, ADD fabric VARCHAR(255) NOT NULL, DROP add_information_id, CHANGE price_id price INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, ttc INT NOT NULL, tva INT NOT NULL, ht INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product ADD add_information_id INT DEFAULT NULL, DROP text, DROP online, DROP fabric, CHANGE price price_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA7456F54 FOREIGN KEY (add_information_id) REFERENCES add_information (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD614C7E7 FOREIGN KEY (price_id) REFERENCES price (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADD614C7E7 ON product (price_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADA7456F54 ON product (add_information_id)');
    }
}
