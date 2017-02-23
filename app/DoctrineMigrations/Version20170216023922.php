<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170216023922 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tipo ADD supervisor_responsavel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tipo ADD CONSTRAINT FK_702D1D478329B06E FOREIGN KEY (supervisor_responsavel_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_702D1D478329B06E ON tipo (supervisor_responsavel_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tipo DROP FOREIGN KEY FK_702D1D478329B06E');
        $this->addSql('DROP INDEX IDX_702D1D478329B06E ON tipo');
        $this->addSql('ALTER TABLE tipo DROP supervisor_responsavel_id');
    }
}
