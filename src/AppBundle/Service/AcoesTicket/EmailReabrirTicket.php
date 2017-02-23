<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\Ticket;

class EmailReabrirTicket extends BaseEmailTicket implements AcaoAoReabrirTicket
{
    public function processaReabertura(Ticket $ticket): void
    {
        $assunto = 'Solução negada - Ticket reaberto';
        $mensagem = $this->getMensagem($ticket);
        $contentType = 'text/plain';
        $charset = 'UTF-8';
        $mensagem = \Swift_Message::newInstance($assunto, $mensagem, $contentType, $charset);
        $mensagem
            ->setFrom('tickets@' . $this->dominio)
            ->setTo($ticket->getAtendenteResponsavel()->getEmail());

        $this->mailer->send($mensagem);
    }

    private function getMensagem(Ticket $ticket): string
    {
        $link = $this->getUrl($ticket);
        $linhas = [];
        $linhas[] = "Prezado, {$ticket->getAtendenteResponsavel()}";
        $linhas[] = '';
        $linhas[] = "Sua solução para o ticket #{$ticket->getId()} foi negada por {$ticket->getUsuarioCriador()} e o mesmo foi reaberto.";
        $linhas[] = "Para mais informações, acesse $link";

        return implode("\n", $linhas);
    }
}