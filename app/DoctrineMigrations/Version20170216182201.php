<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170216182201 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mensagem_ticket (id INT AUTO_INCREMENT NOT NULL, ticket_id INT NOT NULL, autor_id INT NOT NULL, data_hora DATETIME NOT NULL, texto VARCHAR(255) NOT NULL, INDEX IDX_CAD8E288700047D2 (ticket_id), INDEX IDX_CAD8E28814D45BBE (autor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mensagem_ticket ADD CONSTRAINT FK_CAD8E288700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE mensagem_ticket ADD CONSTRAINT FK_CAD8E28814D45BBE FOREIGN KEY (autor_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE ticket DROP descricao');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mensagem_ticket');
        $this->addSql('ALTER TABLE ticket ADD descricao VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
