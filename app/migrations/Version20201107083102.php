<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201107083102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gnode (id INT AUTO_INCREMENT NOT NULL, gnode_type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_490B6B196465950A (gnode_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gnode_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gnode ADD CONSTRAINT FK_490B6B196465950A FOREIGN KEY (gnode_type_id) REFERENCES gnode_type (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gnode DROP FOREIGN KEY FK_490B6B196465950A');
        $this->addSql('DROP TABLE gnode');
        $this->addSql('DROP TABLE gnode_type');
    }
}
