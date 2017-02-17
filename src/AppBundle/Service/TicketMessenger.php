<?php
namespace AppBundle\Service;


use AppBundle\Entity\{MensagemTicket, Ticket};

class TicketMessenger
{
    /**
     * Monta a mensagem para quando o ticket for marcado como resolvido pelo atendente
     *
     * @param Ticket $ticket
     * @return MensagemTicket
     */
    public function getMensagemTicketResolvido(Ticket $ticket): MensagemTicket
    {
        $textoMensagem = "Prezado(a), {$ticket->getUsuarioCriador()}.\n";
        $textoMensagem .= "Este ticket foi marcado como resolvido por {$ticket->getAtendenteResponsavel()}.\n";
        $textoMensagem .= "Por favor, verifique a solução e avalie o atendimento.";
        $mensagem = $this->getMensagem($ticket, $textoMensagem);
        $mensagem
            ->setAutor($ticket->getAtendenteResponsavel());

        return $mensagem;
    }

    /**
     * Monta a mensagem para quando o usuário reprovar uma solução, reabrindo o ticket
     *
     * @param Ticket $ticket
     * @return MensagemTicket
     */
    public function getMensagemTicketReaberto(Ticket $ticket): MensagemTicket
    {
        $textoMensagem = "Ticket reaberto por {$ticket->getUsuarioCriador()}.";
        $mensagem = $this->getMensagem($ticket, $textoMensagem);
        $mensagem
            ->setAutor($ticket->getUsuarioCriador());

        return $mensagem;
    }

    /**
     * Monta a mensagem para quando o usuário aprovar a solução, fechando o ticket
     *
     * @param Ticket $ticket
     * @return MensagemTicket
     */
    public function getMensagemTicketFechado(Ticket $ticket): MensagemTicket
    {
        $textoMensagem = "Ticket fechado por {$ticket->getUsuarioCriador()}";
        $mensagem = $this->getMensagem($ticket, $textoMensagem);
        $mensagem
            ->setAutor($ticket->getUsuarioCriador());

        return $mensagem;
    }

    /**
     * @param Ticket $ticket
     * @param $textoMensagem
     * @return MensagemTicket
     */
    protected function getMensagem(Ticket $ticket, $textoMensagem): MensagemTicket
    {
        $mensagem = new MensagemTicket();
        $mensagem
            ->setTexto($textoMensagem);

        return $mensagem;
    }
}
