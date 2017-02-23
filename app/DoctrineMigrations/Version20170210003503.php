<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration para adição do campo estado, substituindo o campo aberto
 * na tabela ticket.
 */
class Version20170210003503 extends AbstractMigration
{
    /**
     * Realiza a migration, criando o campo estado e removendo o campo aberto
     *
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $ticketTable = $schema->getTable('ticket');
        $ticketTable->addColumn('estado', 'estado_ticket');
        $ticketTable->dropColumn('aberto');
    }

    /**
     * Realiza rollback da migration
     *
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $ticketTable = $schema->getTable('ticket');
        $ticketTable->dropColumn('estado');
        $ticketTable->addColumn('aberto', 'boolean', ['default' => false]);
    }
}
