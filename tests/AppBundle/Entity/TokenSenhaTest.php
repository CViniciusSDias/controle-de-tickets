<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\TokenSenha;
use PHPUnit\Framework\TestCase;
use DateTime;
use DateInterval;

class TokenSenhaTest extends TestCase
{
    public function testTokenAtivoInativo()
    {
        $token = new TokenSenha();
        $token->setExpiracao((new DateTime())->add(new DateInterval('P1D')));

        static::assertTrue($token->isAtivo());
        $token->desativar();
        static::assertFalse($token->isAtivo());

        // Novo token com data de expiração passada
        $token = new TokenSenha();
        $token->setExpiracao(new DateTime());

        static::assertFalse($token->isAtivo());
    }
}