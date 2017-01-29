<?php
namespace AppBundle\Service;

use AppBundle\Entity\{Usuario, RedefinicaoDeSenha};
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Classe que manipula as senhas do usuário
 *
 * @author Vinicius Dias
 * @package AppBundle\Service
 */
class RedefinidorDeSenha
{
    private $encoder;

    /**
     * Construtor
     *
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Codifica e altera a senha de um usuário
     *
     * @param Usuario $usuario
     * @param RedefinicaoDeSenha $redefinicao
     * @throws BadCredentialsException
     */
    public function redefinir(Usuario $usuario, RedefinicaoDeSenha $redefinicao)
    {
        if (!$this->encoder->isPasswordValid($usuario, $redefinicao->getSenhaAtual())) {
            throw new BadCredentialsException('Digite corretamente a senha atual');
        }

        $novaSenha = $this->encoder->encodePassword($usuario, $redefinicao->getNovaSenha());
        $usuario->setSenha($novaSenha);
    }

    /**
     * Codifica a senha de um usuário
     *
     * @param Usuario $usuario
     */
    public function codificar(Usuario $usuario)
    {
        $usuario->setSenha($this->encoder->encodePassword($usuario, $usuario->getSenha()));
    }
}
