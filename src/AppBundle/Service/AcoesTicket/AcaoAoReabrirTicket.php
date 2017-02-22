<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\Ticket;

interface AcaoAoReabrirTicket
{
    public function processaReabertura(Ticket $ticket);
}
