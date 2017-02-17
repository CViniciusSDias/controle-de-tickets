<?php
namespace AppBundle\Entity\EstadoTicket;

use AppBundle\Entity\Ticket;
use AppBundle\Service\TicketMessenger;

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
        $mensagem = (new TicketMessenger())->getMensagemTicketResolvido($ticket);
        $ticket->addMensagem($mensagem);
    }

    public function getDbValue(): int
    {
        return 2;
    }
}
