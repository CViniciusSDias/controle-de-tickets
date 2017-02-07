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
        $twigMock = $this->createMock(TwigEngine::class);

        $routerMock = $this->createMock(Router::class);
        $routerMock
            ->expects($this->once())
            ->method('generate')
            ->with('recuperar_senha', ['token' => null], UrlGeneratorInterface::ABSOLUTE_URL);

        $swiftMock = $this->createMock(\Swift_Mailer::class);
        $email = 'carlosv775@gmail.com';
        $swiftMock
            ->expects($this->once())
            ->method('send');

        $emailRecuperacao = new EmailRecuperacaoSenha($swiftMock, $routerMock, $twigMock);
        /** @var TotkenSenha $tokenSenhaMock */
        $tokenSenhaMock = $this->createMock(TokenSenha::class);
        $emailRecuperacao->sendMail($tokenSenhaMock, $email);
    }
}
