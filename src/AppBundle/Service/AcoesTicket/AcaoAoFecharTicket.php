<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\Ticket;

/**
 * Define uma ação que deve ser executada após fechar um ticket
 *
 * @package AppBundle\Service\AcoesTicket
 * @author Vinicius Dias
 */
interface AcaoAoFecharTicket
{
    /**
     * Método executado para processar o ticket recém fechado
     *
     * @param Ticket $ticket
     */
    public function processaFechamento(Ticket $ticket): void;
}
