<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190422082443 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE resumes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE resumes (id INT NOT NULL, employee_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, is_published BOOLEAN NOT NULL, salary INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CDB8AD338C03F15C ON resumes (employee_id)');
        $this->addSql('ALTER TABLE resumes ADD CONSTRAINT FK_CDB8AD338C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE resumes_id_seq CASCADE');
        $this->addSql('DROP TABLE resumes');
    }
}
