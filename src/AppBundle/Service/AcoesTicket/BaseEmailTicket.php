<?php
namespace AppBundle\Service\AcoesTicket;

use AppBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class BaseEmailTicket
{
    /** @var  \Swift_Mailer */
    protected $mailer;
    /** @var Router $router */
    protected $router;
    /** @var string $dominio */
    protected $dominio;

    public function __construct(\Swift_Mailer $mailer, Router $router, string $dominio)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->dominio = $dominio;
    }

    protected function getUrl(Ticket $ticket)
    {
        return $this->router->generate(
            'visualizar_ticket',
            ['id' => $ticket->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
