<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\Ticket;

interface AcaoAoInteragir
{
    public function processaInteracao(Ticket $ticket);
}
