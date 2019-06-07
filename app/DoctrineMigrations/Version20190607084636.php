<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607084636 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE companies DROP CONSTRAINT fk_8244aa3a41cd9e7a');
        $this->addSql('DROP INDEX idx_8244aa3a41cd9e7a');
        $this->addSql('ALTER TABLE companies DROP employer_id');
        $this->addSql('ALTER TABLE employer ADD company_id INT NOT NULL');
        $this->addSql('ALTER TABLE employer ADD CONSTRAINT FK_DE4CF066979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DE4CF066979B1AD6 ON employer (company_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE employer DROP CONSTRAINT FK_DE4CF066979B1AD6');
        $this->addSql('DROP INDEX IDX_DE4CF066979B1AD6');
        $this->addSql('ALTER TABLE employer DROP company_id');
        $this->addSql('ALTER TABLE companies ADD employer_id INT NOT NULL');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT fk_8244aa3a41cd9e7a FOREIGN KEY (employer_id) REFERENCES employer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8244aa3a41cd9e7a ON companies (employer_id)');
    }
}
