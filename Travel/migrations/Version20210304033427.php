<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304033427 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan_evenement (plan_id INT NOT NULL, evenement_id INT NOT NULL, INDEX IDX_BBF8950BE899029B (plan_id), INDEX IDX_BBF8950BFD02F13 (evenement_id), PRIMARY KEY(plan_id, evenement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan_hotel (plan_id INT NOT NULL, hotel_id INT NOT NULL, INDEX IDX_50F51B25E899029B (plan_id), INDEX IDX_50F51B253243BB18 (hotel_id), PRIMARY KEY(plan_id, hotel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan_facceuil (plan_id INT NOT NULL, facceuil_id INT NOT NULL, INDEX IDX_AE092600E899029B (plan_id), INDEX IDX_AE09260028FA2675 (facceuil_id), PRIMARY KEY(plan_id, facceuil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan_guide (plan_id INT NOT NULL, guide_id INT NOT NULL, INDEX IDX_993882C9E899029B (plan_id), INDEX IDX_993882C9D7ED1D4B (guide_id), PRIMARY KEY(plan_id, guide_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plan_evenement ADD CONSTRAINT FK_BBF8950BE899029B FOREIGN KEY (plan_id) REFERENCES plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plan_evenement ADD CONSTRAINT FK_BBF8950BFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plan_hotel ADD CONSTRAINT FK_50F51B25E899029B FOREIGN KEY (plan_id) REFERENCES plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plan_hotel ADD CONSTRAINT FK_50F51B253243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plan_facceuil ADD CONSTRAINT FK_AE092600E899029B FOREIGN KEY (plan_id) REFERENCES plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plan_facceuil ADD CONSTRAINT FK_AE09260028FA2675 FOREIGN KEY (facceuil_id) REFERENCES facceuil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plan_guide ADD CONSTRAINT FK_993882C9E899029B FOREIGN KEY (plan_id) REFERENCES plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plan_guide ADD CONSTRAINT FK_993882C9D7ED1D4B FOREIGN KEY (guide_id) REFERENCES guide (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plan_evenement DROP FOREIGN KEY FK_BBF8950BE899029B');
        $this->addSql('ALTER TABLE plan_hotel DROP FOREIGN KEY FK_50F51B25E899029B');
        $this->addSql('ALTER TABLE plan_facceuil DROP FOREIGN KEY FK_AE092600E899029B');
        $this->addSql('ALTER TABLE plan_guide DROP FOREIGN KEY FK_993882C9E899029B');
        $this->addSql('DROP TABLE plan');
        $this->addSql('DROP TABLE plan_evenement');
        $this->addSql('DROP TABLE plan_hotel');
        $this->addSql('DROP TABLE plan_facceuil');
        $this->addSql('DROP TABLE plan_guide');
    }
}
