<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Token de redefinição de senha
 *
 * @author Vinicius Dias
 * @package AppBundle\Entity
 * @ORM\Table(name="token_senha")
 * @ORM\Entity
 */
class TokenSenha
{
    use ModelTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiracao", type="datetime")
     */
    private $expiracao;

    /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     */
    private $usuario;

    /**
     * @var bool
     *
     * @ORM\Column(name="ativo", type="boolean")
     */
    private $ativo;

    public function __construct()
    {
        $this->ativo = true;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return TokenSenha
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Set expiracao
     *
     * @param DateTime $expiracao
     *
     * @return TokenSenha
     */
    public function setExpiracao(DateTime $expiracao): self
    {
        $this->expiracao = $expiracao;

        return $this;
    }

    /**
     * Desativa o token
     */
    public function desativar(): void
    {
        $this->ativo = false;
    }

    /**
     * Verifica se o token ainda está ativo
     *
     * @return bool
     */
    public function isAtivo(): bool
    {
        return $this->expiracao->getTimestamp() > (new DateTime())->getTimestamp() && $this->ativo;
    }

    /**
     * Set usuario
     *
     * @param Usuario $usuario
     * @return TokenSenha
     */
    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return Usuario|null
     */
    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }
}
