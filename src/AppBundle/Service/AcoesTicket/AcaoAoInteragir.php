<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\Ticket;

/**
 * Define uma ação executada após interagir (enviar mensagem) com um ticket
 *
 * @package AppBundle\Service\AcoesTicket
 * @author Vinicius Dias
 */
interface AcaoAoInteragir
{
    /**
     * Método executado para processar um ticket que recebeu uma interação
     *
     * @param Ticket $ticket
     */
    public function processaInteracao(Ticket $ticket): void;
}
