<?php
namespace AppBundle\Service;

use AppBundle\Entity\{Usuario, RedefinicaoDeSenha};
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class RedefinidorDeSenha
{
    private $encoder;

    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function redefinir(Usuario $usuario, RedefinicaoDeSenha $redefinicao)
    {
        if (!$this->encoder->isPasswordValid($usuario, $redefinicao->getSenhaAtual())) {
            throw new \Exception('Digite corretamente a senha atual');
        }

        $novaSenha = $this->encoder->encodePassword($usuario, $redefinicao->getNovaSenha());
        $usuario->setSenha($novaSenha);
    }

    public function codificar(Usuario $usuario)
    {
        $usuario->setSenha($this->encoder->encodePassword($usuario, $usuario->getSenha()));
    }
}
