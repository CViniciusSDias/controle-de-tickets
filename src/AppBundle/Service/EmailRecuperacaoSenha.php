<?php
namespace AppBundle\Service;

use AppBundle\Entity\{MensagemRecuperacaoSenha, TokenSenha};
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Classe que envia o e-mail de recuperação de senha
 *
 * @author Vinicius Dias
 * @package AppBundle\Service
 */
class EmailRecuperacaoSenha
{
    private $mailer;
    private $router;
    private $twig;

    /**
     * Construtor
     *
     * @param \Swift_Mailer $mailer
     * @param Router $router
     * @param TwigEngine $twig
     */
    public function __construct(\Swift_Mailer $mailer, Router $router, TwigEngine $twig)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * Envia o e-mail com o token de redefinição de senha
     *
     * @param TokenSenha $token
     * @param string $email
     */
    public function sendMail(TokenSenha $token, string $email)
    {
        $link = $this->router->generate(
            'recuperar_senha',
            ['token' => $token->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        /** @var \Swift_Mime_Message $mensagem */
        $mensagem = MensagemRecuperacaoSenha::newInstance()
            ->setFrom('recuperacao@zer0.w.pw')
            ->setTo($email)
            ->setBody($this->twig->render('seguranca/email-recuperacao.html.twig', ['link' => $link]));

        $this->mailer->send($mensagem);
    }
}
