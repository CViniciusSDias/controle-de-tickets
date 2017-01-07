<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @ORM\Column(name="titulo", type="string", length=128)
     * @Assert\Length(min=8, minMessage="O título deve conter pelo menos 8 caracteres")
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=255, nullable=true)
     */
    private $descricao;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aberto", type="boolean")
     */
    private $aberto;

    /**
     * @var int
     *
     * @ORM\Column(name="prioridade", type="smallint")
     * @Assert\LessThan(value=6, message="A prioridade deve ser entre 0 e 5")
     * @Assert\GreaterThanOrEqual(value=0, message="A prioridade deve ser entre 0 e 5")
     */
    private $prioridade;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataHora", type="datetime")
     */
    private $dataHora;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="previsaoResposta", type="datetime", nullable=true)
     */
    private $previsaoResposta;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="usuario_criador")
     */
    private $usuarioCriador;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="atendente_responsavel", nullable=true)
     */
    private $atendenteResponsavel;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria")
     */
    private $categoria;

    public function __construct()
    {
        $this->aberto = true;
        $this->prioridade = 3;
        $this->dataHora = new DateTime();
    }

    public function getStatus(): string
    {
        return $this->aberto ? 'aberto' : 'fechado';
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
     * Set titulo
     *
     * @param string $titulo
     * @return Ticket
     */
    public function setTitulo(string $titulo): self
    {
        if (strlen($titulo) < 8)
            throw new \InvalidArgumentException('O título deve conter pelo menos 8 caracteres');

        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     * @return Ticket
     */
    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string
     */
    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    /**
     * Set aberto
     *
     * @param boolean $aberto
     * @return Ticket
     */
    public function setAberto(bool $aberto)
    {
        $this->aberto = $aberto;

        return $this;
    }

    /**
     * Get aberto
     * @return boolean
     */
    public function getAberto(): bool
    {
        return $this->aberto;
    }

    /**
     * Set prioridade
     *
     * @param integer $prioridade
     * @return Ticket
     */
    public function setPrioridade(int $prioridade): self
    {
        if ($prioridade < 0 || $prioridade > 5)
            throw new InvalidArgumentException('A prioridade deve ser entre 0 e 5');

        $this->prioridade = $prioridade;

        return $this;
    }

    /**
     * Get prioridade
     *
     * @return integer
     */
    public function getPrioridade(): int
    {
        return $this->prioridade;
    }

    /**
     * Set dataHora
     *
     * @param \DateTime $dataHora
     * @return Ticket
     */
    public function setDataHora(DateTime $dataHora): self
    {
        $this->dataHora = $dataHora;

        return $this;
    }

    /**
     * Get dataHora
     * @return \DateTime
     */
    public function getDataHora(): DateTime
    {
        return $this->dataHora;
    }

    /**
     * Set previsaoResposta
     *
     * @param \DateTime $previsaoResposta
     *
     * @return Ticket
     */
    public function setPrevisaoResposta(DateTime $previsaoResposta): self
    {
        $this->previsaoResposta = $previsaoResposta;

        return $this;
    }

    /**
     * Get previsaoResposta
     *
     * @return \DateTime
     */
    public function getPrevisaoResposta(): ?DateTime
    {
        return $this->previsaoResposta;
    }

    /**
     * Set usuarioCriador
     *
     * @param Usuario $usuarioCriador
     * @return Ticket
     */
    public function setUsuarioCriador(Usuario $usuarioCriador = null): self
    {
        $this->usuarioCriador = $usuarioCriador;

        return $this;
    }

    /**
     * Get usuarioCriador
     *
     * @return Usuario
     */
    public function getUsuarioCriador(): Usuario
    {
        return $this->usuarioCriador;
    }

    /**
     * Set atendenteResponsavel
     *
     * @param Usuario $atendenteResponsavel
     *
     * @return Ticket
     */
    public function setAtendenteResponsavel(Usuario $atendenteResponsavel): self
    {
        $this->atendenteResponsavel = $atendenteResponsavel;

        return $this;
    }

    /**
     * Get atendenteResponsavel
     *
     * @return Usuario
     */
    public function getAtendenteResponsavel(): Usuario
    {
        return $this->atendenteResponsavel;
    }

    /**
     * Set categoria
     *
     * @param Categoria $categoria
     *
     * @return Ticket
     */
    public function setCategoria(Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return Categoria
     */
    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }
}
