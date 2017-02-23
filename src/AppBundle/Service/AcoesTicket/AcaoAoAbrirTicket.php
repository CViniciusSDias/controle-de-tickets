<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\Ticket;

/**
 * Define uma ação que deve ser realizada após abrir um ticket
 *
 * @package AppBundle\Service\AcoesTicket
 * @author Vinicius Dias
 */
interface AcaoAoAbrirTicket
{
    /**
     * Método executado para processar o ticket recém aberto
     *
     * @param Ticket $ticket
     */
    public function processaAbertura(Ticket $ticket): void;
}
