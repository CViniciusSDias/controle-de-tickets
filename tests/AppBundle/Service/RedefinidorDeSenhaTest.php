<?php
namespace Tests\AppBundle\Service;

use AppBundle\Entity\RedefinicaoDeSenha;
use AppBundle\Entity\Usuario;
use AppBundle\Service\RedefinidorDeSenha;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class RedefinidorDeSenhaTest extends KernelTestCase
{
    /** @var UserPasswordEncoder $encoder */
    private $encoder;

    public function setUp()
    {
        static::bootKernel();
        $this->encoder = static::$kernel
            ->getContainer()
            ->get('security.password_encoder');
    }

    public function testRedefineSenha()
    {
        $encoder = $this->encoder;

        $usuario = new Usuario();
        $usuario
            ->setSenha($encoder->encodePassword($usuario, 'senha'));

        $redefinicao = new RedefinicaoDeSenha();
        $redefinicao
            ->setSenhaAtual('senha')
            ->setNovaSenha('admin');

        $redefinidor = new RedefinidorDeSenha($encoder);
        $redefinidor->redefinir($usuario, $redefinicao);

        static::assertTrue($encoder->isPasswordValid($usuario, 'admin'));
    }

    public function testCodificaSenha()
    {
        $usuario = new Usuario();
        $usuario->setSenha('senha');

        $redefinidor = new RedefinidorDeSenha($this->encoder);
        $redefinidor->codificar($usuario);

        static::assertTrue($this->encoder->isPasswordValid($usuario, 'senha'));
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testUsuarioComSenhaErradaDeveLancarExcecao()
    {
        $usuario = new Usuario();
        $usuario->setSenha('teste');

        $redefinicao = new RedefinicaoDeSenha();
        $redefinicao->setSenhaAtual('senha');

        $redefinidor = new RedefinidorDeSenha($this->encoder);
        $redefinidor->redefinir($usuario, $redefinicao);
    }
}
