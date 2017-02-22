<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\Ticket;

interface AcaoAoAbrirTicket
{
    public function processa(Ticket $ticket);
}
