<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\{
    MensagemTicket, Ticket, Usuario
};

class EmailInteracaoTicket extends BaseEmailTicket implements AcaoAoInteragir
{
    public function processaInteracao(Ticket $ticket): void
    {
        $remetente = $this->getRemetente($ticket);
        $destinatario = $this->getDestinatario($ticket);
        $assunto = 'Interação em ticket';
        $mensagem = $this->getMensagem($ticket, $remetente, $destinatario);
        $contentType = 'text/plain';
        $charset = 'UTF-8';
        $mensagem = \Swift_Message::newInstance($assunto, $mensagem, $contentType, $charset);
        $mensagem
            ->setFrom('tickets@' . $this->dominio)
            ->setTo($destinatario->getEmail());

        $this->mailer->send($mensagem);
    }

    private function getMensagem(Ticket $ticket, Usuario $remetente, Usuario $destinatario): string
    {
        $link = $this->getUrl($ticket);
        $linhas = [];
        $linhas[] = "Prezado $destinatario.";
        $linhas[] = '';
        $linhas[] = "$remetente interagiu no ticket #{$ticket->getId()}.";
        $linhas[] = "Para mais informações, acesse $link";

        return implode("\n", $linhas);
    }

    private function getRemetente(Ticket $ticket): Usuario
    {
        $mensagens = $ticket->getMensagens();
        /** @var MensagemTicket $ultimaMensagem */
        $ultimaMensagem = $mensagens[count($mensagens) - 1];

        return $ultimaMensagem->getAutor();
    }

    private function getDestinatario(Ticket $ticket): Usuario
    {

        $remetente = $this->getRemetente($ticket);

        return $remetente == $ticket->getAtendenteResponsavel()
            ? $ticket->getUsuarioCriador() : $ticket->getAtendenteResponsavel();
    }
}