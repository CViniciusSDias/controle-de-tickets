<?php
namespace AppBundle\Entity\EstadoTicket;

use AppBundle\Entity\Ticket;

class EmAndamento implements EstadoTicket
{
    public function getCor(): string
    {
        return 'orange';
    }

    public function __toString(): string
    {
        return 'Em Andamento';
    }

    public function ehAberto(): bool
    {
        return true;
    }

    public function fechar(Ticket $ticket): void
    {
        $ticket->setEstado(new AguardandoAprovacao());
    }

    public function getDbValue(): int
    {
        return 2;
    }
}
