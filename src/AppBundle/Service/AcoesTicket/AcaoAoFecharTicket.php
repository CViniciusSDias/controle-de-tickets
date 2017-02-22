<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\Ticket;

interface AcaoAoFecharTicket
{
    public function processaFechamento(Ticket $ticket);
}
