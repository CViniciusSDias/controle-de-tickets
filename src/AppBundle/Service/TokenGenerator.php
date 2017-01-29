<?php
namespace AppBundle\Service;

use AppBundle\Entity\TokenSenha;
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
    private $doctrine;

    /**
     * Construtor
     *
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Gera um token de recuperação de senha parar o usuário com o e-mail informado
     *
     * @param string $email
     * @return TokenSenha
     */
    public function generateToken(string $email): TokenSenha
    {
        $usuarioRepository = $this->doctrine->getRepository('AppBundle:Usuario');
        $usuario = $usuarioRepository->findOneBy(['email' => $email]);

        $token = new TokenSenha();
        $token
            ->setToken(sha1(time()))
            ->setExpiracao((new DateTime())
            ->add(new DateInterval('P1D')))
            ->setUsuario($usuario);

        return $token;
    }
}
