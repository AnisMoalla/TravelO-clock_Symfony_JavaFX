<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401091135 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guide ADD vote INT DEFAULT NULL, ADD rate DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAAF7688D5');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAAF7688D5 FOREIGN KEY (reclamation_famille_id) REFERENCES reclamation_facc (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guide DROP vote, DROP rate');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAAF7688D5');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAAF7688D5 FOREIGN KEY (reclamation_famille_id) REFERENCES reclamation_facc (id)');
    }
}
