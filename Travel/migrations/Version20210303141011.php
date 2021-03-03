<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210303141011 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1EF69B0CD');
        $this->addSql('CREATE TABLE postforum (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, category INT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_115BA9DDA76ED395 (user_id), INDEX IDX_115BA9DD4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE postforum ADD CONSTRAINT FK_115BA9DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE postforum ADD CONSTRAINT FK_115BA9DD4B89032C FOREIGN KEY (category) REFERENCES category (id)');
        $this->addSql('DROP TABLE post_frum');
        $this->addSql('DROP INDEX IDX_64C19C1EF69B0CD ON category');
        $this->addSql('ALTER TABLE category DROP id_post_f_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_frum (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE postforum');
        $this->addSql('ALTER TABLE category ADD id_post_f_id INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1EF69B0CD FOREIGN KEY (id_post_f_id) REFERENCES post_frum (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1EF69B0CD ON category (id_post_f_id)');
    }
}
