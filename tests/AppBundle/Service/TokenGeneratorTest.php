<?php
namespace Tests\AppBundle\Service;

use AppBundle\Entity\TokenSenha;
use AppBundle\Entity\Usuario;
use AppBundle\Repository\UsuarioRepository;
use AppBundle\Service\TokenGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Teste de integração do serviço TokenGenerator
 *
 * @package tests\AppBundle\Service
 * @author Vinicius Dias
 */
class TokenGeneratorTest extends TestCase
{
    public function testGenerateToken()
    {
        $usuario = new Usuario();
        $mockRepository = $this->createMock(UsuarioRepository::class);
        $mockRepository->method('findOneBy')->willReturn($usuario);
        $mockDoctrine = $this->createMock(RegistryInterface::class);
        $mockDoctrine->method('getRepository')->willReturn($mockRepository);

        $tokenGenerator = new TokenGenerator($mockDoctrine);
        $token = $tokenGenerator->generateToken('');

        static::assertTrue($token instanceof TokenSenha);
        static::assertEquals($usuario, $token->getUsuario());
        static::assertTrue($token->isAtivo());
    }
}
