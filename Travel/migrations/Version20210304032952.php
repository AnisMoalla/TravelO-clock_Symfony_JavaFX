<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304032952 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E71F7E88B');
        $this->addSql('ALTER TABLE facceuil DROP FOREIGN KEY FK_A06C117A28FA2675');
        $this->addSql('ALTER TABLE guide DROP FOREIGN KEY FK_CA9EC735D7ED1D4B');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED93243BB18');
        $this->addSql('DROP TABLE plan');
        $this->addSql('DROP INDEX IDX_B26681E71F7E88B ON evenement');
        $this->addSql('ALTER TABLE evenement DROP event_id');
        $this->addSql('DROP INDEX IDX_A06C117A28FA2675 ON facceuil');
        $this->addSql('ALTER TABLE facceuil DROP facceuil_id');
        $this->addSql('DROP INDEX IDX_CA9EC735D7ED1D4B ON guide');
        $this->addSql('ALTER TABLE guide DROP guide_id');
        $this->addSql('DROP INDEX IDX_3535ED93243BB18 ON hotel');
        $this->addSql('ALTER TABLE hotel DROP hotel_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE evenement ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E71F7E88B FOREIGN KEY (event_id) REFERENCES plan (id)');
        $this->addSql('CREATE INDEX IDX_B26681E71F7E88B ON evenement (event_id)');
        $this->addSql('ALTER TABLE facceuil ADD facceuil_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facceuil ADD CONSTRAINT FK_A06C117A28FA2675 FOREIGN KEY (facceuil_id) REFERENCES plan (id)');
        $this->addSql('CREATE INDEX IDX_A06C117A28FA2675 ON facceuil (facceuil_id)');
        $this->addSql('ALTER TABLE guide ADD guide_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE guide ADD CONSTRAINT FK_CA9EC735D7ED1D4B FOREIGN KEY (guide_id) REFERENCES plan (id)');
        $this->addSql('CREATE INDEX IDX_CA9EC735D7ED1D4B ON guide (guide_id)');
        $this->addSql('ALTER TABLE hotel ADD hotel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED93243BB18 FOREIGN KEY (hotel_id) REFERENCES plan (id)');
        $this->addSql('CREATE INDEX IDX_3535ED93243BB18 ON hotel (hotel_id)');
    }
}
