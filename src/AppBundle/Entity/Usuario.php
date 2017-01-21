<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use InvalidArgumentException;
use DateTime;

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
     * @Assert\Choice({"ROLE_USER", "ROLE_ADMIN", "ROLE_SUPER_ADMIN"}, strict=true)
     */
    private $tipo;

    /**
     * @var \DateTime
     * @ORM\Column(name="data_cadastro", type="datetime")
     */
    private $dataCadastro;

    public function __construct()
    {
        $this->dataCadastro = new DateTime();
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
    {
    }

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

    /**
     * @see \Serializable::unserialize()
     * @param string $serialized String serializada contendo as informações do usuário
     */
    public function unserialize($serialized)
    {
        list ($this->id, $this->email, $this->senha, $this->nome) = unserialize($serialized);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return Usuario
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('E-mail inválido');
        }

        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set senha
     *
     * @param string $senha
     * @return Usuario
     */
    public function setSenha(string $senha): self
    {
        $this->senha = $senha;

        return $this;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Usuario
     */
    public function setTipo(string $tipo): self
    {
        if (!in_array($tipo, array('ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'))) {
            throw new InvalidArgumentException('Tipo inválido. Deve ser ROLE_USER, ROLE_ADMIN ou ROLE_SUPER_ADMIN');
        }
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo(): string
    {
        $tipos = [
            '' => '',
            'ROLE_USER' => 'Usuário',
            'ROLE_ADMIN' => 'Suporte',
            'ROLE_SUPER_ADMIN' => 'Administrador'
        ];

        return $tipos[$this->tipo];
    }

    /**
     * Get senha
     *
     * @return string
     */
    public function getSenha(): ?string
    {
        return $this->senha;
    }

    /**
     * Set dataCadastro
     *
     * @param \DateTime $dataCadastro
     * @return Usuario
     */
    public function setDataCadastro(DateTime $dataCadastro): self
    {
        $this->dataCadastro = $dataCadastro;

        return $this;
    }

    /**
     * Get dataCadastro
     *
     * @return \DateTime
     */
    public function getDataCadastro(): DateTime
    {
        return $this->dataCadastro;
    }
}
