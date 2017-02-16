<?php
namespace AppBundle\Entity\EstadoTicket;

use AppBundle\Entity\Ticket;

class AguardandoAprovacao implements EstadoTicket
{
    public function getCor(): string
    {
        return 'blue';
    }

    public function __toString(): string
    {
        return 'Respondido';
    }

    public function ehAberto(): bool
    {
        return false;
    }

    public function fechar(Ticket $ticket): void
    {
        $ticket->setEstado(new Fechado());
    }

    public function getDbValue(): int
    {
        return 3;
    }
}
