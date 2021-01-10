<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201108092204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gedge ADD gedge_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gedge ADD CONSTRAINT FK_B972503ADDE10D43 FOREIGN KEY (gedge_type_id) REFERENCES gedge_type (id)');
        $this->addSql('CREATE INDEX IDX_B972503ADDE10D43 ON gedge (gedge_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gedge DROP FOREIGN KEY FK_B972503ADDE10D43');
        $this->addSql('DROP INDEX IDX_B972503ADDE10D43 ON gedge');
        $this->addSql('ALTER TABLE gedge DROP gedge_type_id');
    }
}
