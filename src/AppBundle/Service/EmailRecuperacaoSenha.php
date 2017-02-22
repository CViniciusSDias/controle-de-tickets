<?php
namespace AppBundle\Service;

use AppBundle\Entity\MensagemRecuperacaoSenha;
use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * Classe que envia o e-mail de recuperação de senha
 *
 * @author Vinicius Dias
 * @package AppBundle\Service
 */
class EmailRecuperacaoSenha
{
    /** @var \Swift_Mailer $mailer */
    private $mailer;
    /** @var TwigEngine $twig */
    private $twig;
    /** @var string $dominio */
    private $dominio;

    /**
     * Construtor
     *
     * @param \Swift_Mailer $mailer
     * @param TwigEngine $twig
     */
    public function __construct(\Swift_Mailer $mailer, TwigEngine $twig, string $dominio)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->dominio = $dominio;
    }

    /**
     * Envia o e-mail com o token de redefinição de senha
     *
     * @param string $link Link de recuperação da senha
     * @param string $email
     */
    public function sendMail(string $link, string $email)
    {
        /** @var \Swift_Mime_Message $mensagem */
        $mensagem = MensagemRecuperacaoSenha::newInstance()
            ->setFrom('recuperacao@' . $this->dominio)
            ->setTo($email)
            ->setBody($this->twig->render('seguranca/email-recuperacao.html.twig', ['link' => $link]));

        $this->mailer->send($mensagem);
    }
}
