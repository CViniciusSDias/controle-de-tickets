<?php
namespace AppBundle\Entity\EstadoTicket;

use AppBundle\Entity\Ticket;

class Aberto implements EstadoTicket
{
    public function getCor(): string
    {
        return 'red';
    }

    public function __toString(): string
    {
        return 'Aberto';
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
        return 1;
    }
}