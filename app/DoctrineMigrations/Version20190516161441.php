<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516161441 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE filters_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE options_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE filters (id INT NOT NULL, name VARCHAR(255) NOT NULL, name_field VARCHAR(255) NOT NULL, is_published BOOLEAN NOT NULL, folded BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE options (id INT NOT NULL, filter_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D035FA87D395B25E ON options (filter_id)');
        $this->addSql('ALTER TABLE options ADD CONSTRAINT FK_D035FA87D395B25E FOREIGN KEY (filter_id) REFERENCES filters (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE options DROP CONSTRAINT FK_D035FA87D395B25E');
        $this->addSql('DROP SEQUENCE filters_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE options_id_seq CASCADE');
        $this->addSql('DROP TABLE filters');
        $this->addSql('DROP TABLE options');
    }
}
