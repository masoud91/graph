<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201107085226 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gedge (id INT AUTO_INCREMENT NOT NULL, from_node_id INT DEFAULT NULL, to_node_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_B972503AC0537C78 (from_node_id), INDEX IDX_B972503AC895A222 (to_node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gedge_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gedge ADD CONSTRAINT FK_B972503AC0537C78 FOREIGN KEY (from_node_id) REFERENCES gnode (id)');
        $this->addSql('ALTER TABLE gedge ADD CONSTRAINT FK_B972503AC895A222 FOREIGN KEY (to_node_id) REFERENCES gnode (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gedge');
        $this->addSql('DROP TABLE gedge_type');
    }
}
