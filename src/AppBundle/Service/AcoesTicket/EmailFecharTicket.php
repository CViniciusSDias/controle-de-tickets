<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\{
    MensagemTicket, Ticket, Usuario
};

class EmailFecharTicket extends BaseEmailTicket implements AcaoAoFecharTicket
{
    public function processaFechamento(Ticket $ticket): void
    {
        $ultimaMensagem = $this->getUltimaMensagem($ticket);
        $usuarioFechou = $ultimaMensagem->getAutor() == $ticket->getUsuarioCriador();
        $remetente = $usuarioFechou ? $ticket->getUsuarioCriador() : $ticket->getAtendenteResponsavel();
        $destinatario = $remetente == $ticket->getAtendenteResponsavel() ? $ticket->getUsuarioCriador()
            : $ticket->getAtendenteResponsavel();

        $assunto = $usuarioFechou ? 'Ticket fechado' : 'Ticket solucionado';
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
        $linhas[] = "O ticket #{$ticket->getId()} foi marcado como fechado por {$remetente}.";
        $linhas[] = "Para mais detalhes, acesse $link";

        return implode("\n", $linhas);
    }

    private function getUltimaMensagem(Ticket $ticket): MensagemTicket
    {
        $mensagens = $ticket->getMensagens();

        return $mensagens[count($mensagens) - 1];
    }
}
