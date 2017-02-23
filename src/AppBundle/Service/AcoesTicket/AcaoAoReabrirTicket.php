<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\Ticket;

/**
 * Define uma ação que deve ser realizada após reabrir um ticket
 *
 * @package AppBundle\Service\AcoesTicket
 * @author Vinicius Dias
 */
interface AcaoAoReabrirTicket
{
    /**
     * Método executado para processar um ticket que acaba de ser reaberto
     *
     * @param Ticket $ticket
     */
    public function processaReabertura(Ticket $ticket): void;
}
