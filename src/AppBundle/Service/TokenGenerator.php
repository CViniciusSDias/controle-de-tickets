<?php
namespace AppBundle\Service;

use AppBundle\Entity\TokenSenha;
use AppBundle\Repository\UsuarioRepository;
use DateTime;
use DateInterval;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Classe geradora de Token para recuperação de senha
 *
 * @author Vinicius Dias
 * @package AppBundle\Service
 */
class TokenGenerator
{
    private $usuarioRepo;

    /**
     * Construtor
     *
     * @param UsuarioRepository $usuarioRepo
     */
    public function __construct(UsuarioRepository $usuarioRepo)
    {
        $this->usuarioRepo = $usuarioRepo;
    }

    /**
     * Gera um token de recuperação de senha parar o usuário com o e-mail informado
     *
     * @param string $email
     * @return TokenSenha
     */
    public function generateToken(string $email): TokenSenha
    {
        $usuario = $this->usuarioRepo->findByEmail($email);

        $token = new TokenSenha();
        $token
            ->setToken(sha1(time()))
            ->setExpiracao((new DateTime())->add(new DateInterval('P1D')))
            ->setUsuario($usuario);

        return $token;
    }
}
