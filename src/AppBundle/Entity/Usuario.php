<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsuarioRepository")
 */
class Usuario implements UserInterface, \Serializable
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
     * @ORM\Column(name="nome", type="string", length=128)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="senha", type="string", length=64)
     */
    private $senha;

    /**
     * @var string
     * @ORM\Column(
     *     name="tipo",
     *     type="string",
     *     columnDefinition="ENUM('ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN')"
     * )
     * @Assert\Choice({"ROLE_USER", "ROLE_ADMIN", "ROLE_SUPER_ADMIN"})
     */
    private $tipo;

    public function __construct()
    {
        $this->grupos = new ArrayCollection();
    }

    public function getRoles()
    {
        return array($this->tipo);
    }

    public function getPassword()
    {
        return $this->senha;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {}

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->senha,
            $this->nome
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list ($this->id, $this->email, $this->senha, $this->nome) = unserialize($serialized);
    }
}

