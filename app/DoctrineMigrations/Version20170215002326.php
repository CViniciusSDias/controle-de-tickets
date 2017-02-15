<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170215002326 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA33397707A');
        $this->addSql('DROP INDEX IDX_97A0ADA33397707A ON ticket');
        $this->addSql('RENAME TABLE categoria TO tipo;');
        $this->addSql('ALTER TABLE ticket CHANGE estado estado INT  DEFAULT 1 NOT NULL COMMENT \'(DC2Type:estado_ticket)\', CHANGE categoria_id tipo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3A9276E6C ON ticket (tipo_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3A9276E6C');
        $this->addSql('DROP INDEX IDX_97A0ADA3A9276E6C ON ticket');
        $this->addSql('RENAME TABLE tipo TO categoria;');
        $this->addSql('ALTER TABLE ticket CHANGE estado estado INT DEFAULT 1 NOT NULL, CHANGE tipo_id categoria_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA33397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA33397707A ON ticket (categoria_id)');
    }
}
