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
    private $mailer;
    private $twig;

    /**
     * Construtor
     *
     * @param \Swift_Mailer $mailer
     * @param TwigEngine $twig
     */
    public function __construct(\Swift_Mailer $mailer, TwigEngine $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
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
            ->setFrom('recuperacao@zer0.w.pw')
            ->setTo($email)
            ->setBody($this->twig->render('seguranca/email-recuperacao.html.twig', ['link' => $link]));

        $this->mailer->send($mensagem);
    }
}
