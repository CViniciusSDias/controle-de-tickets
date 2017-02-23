<?php
namespace Tests\AppBundle\Service;

use AppBundle\Entity\TokenSenha;
use AppBundle\Service\EmailRecuperacaoSenha;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailRecuperacaoSenhaTest extends TestCase
{
    public function testEnviaEmailRecuperacao()
    {
        /** @var TwigEngine $twigMock */
        $twigMock = $this->createMock(TwigEngine::class);
        /** @var \Swift_Mailer $swiftMock */
        $swiftMock = $this->createMock(\Swift_Mailer::class);

        $email = 'carlosv775@gmail.com';
        $swiftMock
            ->expects($this->once())
            ->method('send');

        $emailRecuperacao = new EmailRecuperacaoSenha($swiftMock, $twigMock, 'zer0.w.pw');
        $emailRecuperacao->sendMail('', $email);
    }
}
