<?php
namespace AppBundle\Service\AcoesTicket;


use AppBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailTicketAberto implements AcaoAoAbrirTicket
{
    /** @var  \Swift_Mailer $mailer */
    private $mailer;
    private $router;

    public function __construct(\Swift_Mailer $mailer, Router $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function processaAbertura(Ticket $ticket): void
    {
        $assunto = 'Ticket aberto';
        $mensagem = $this->getMensagem($ticket);
        $contentType = 'text/plain';
        $charset = 'UTF-8';
        $mensagem = \Swift_Message::newInstance($assunto, $mensagem, $contentType, $charset);
        $mensagem
            ->setFrom('tickets@zer0.w.pw')
            ->setTo($ticket->getAtendenteResponsavel()->getEmail());

        $this->mailer->send($mensagem);
    }

    private function getMensagem(Ticket $ticket): string
    {
        $link = $this->router->generate('visualizar_ticket', ['id' => $ticket->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $linhas = [];
        $linhas[] = "Prezado, {$ticket->getAtendenteResponsavel()}.";
        $linhas[] = '';
        $linhas[] = "Um novo ticket foi aberto por {$ticket->getUsuarioCriador()} e está sob sua responsabilidade.";
        $linhas[] = "Para mais informações, acesse $link";


        return implode("\n", $linhas);
    }
}
