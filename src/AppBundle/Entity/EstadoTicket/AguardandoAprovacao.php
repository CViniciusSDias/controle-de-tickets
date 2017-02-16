<?php
namespace AppBundle\Entity\EstadoTicket;

use AppBundle\Entity\Ticket;

class AguardandoAprovacao implements EstadoTicket
{
    public function getCor(): string
    {
        return 'green';
    }

    /**
     * Indica para o cliente que o ticket foi respondido, fazendo com que ele confirme ou negue a solução
     *
     * @return string
     */
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
