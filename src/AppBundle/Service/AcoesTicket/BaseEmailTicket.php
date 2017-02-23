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

    /**
     * Inicializa os serviços de rota e de e-mail, além do domínio utilizado para enviar os e-mails
     *
     * @param \Swift_Mailer $mailer
     * @param Router $router
     * @param string $dominio
     */
    public function __construct(\Swift_Mailer $mailer, Router $router, string $dominio)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->dominio = $dominio;
    }

    /**
     * Monta a URL para visualização do Ticket
     *
     * @param Ticket $ticket
     * @return string
     */
    protected function getUrl(Ticket $ticket): string
    {
        return $this->router->generate(
            'visualizar_ticket',
            ['id' => $ticket->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
