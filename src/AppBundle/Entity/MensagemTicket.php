<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classe que representa uma mensagem (interação) em um ticket
 *
 * @ORM\Entity
 * @package AppBundle\Entity
 * @author Vinicius Dias
 */
class MensagemTicket
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Ticket
     *
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="mensagens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket;

    /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $autor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dataHora;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $texto;

    public function __construct()
    {
        $this->dataHora = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Ticket
     */
    public function getTicket(): Ticket
    {
        return $this->ticket;
    }

    /**
     * @param Ticket $ticket
     * @return MensagemTicket
     */
    public function setTicket(Ticket $ticket): self
    {
        $this->ticket = $ticket;
        return $this;
    }

    /**
     * @return Usuario
     */
    public function getAutor(): Usuario
    {
        return $this->autor;
    }

    /**
     * @param Usuario $autor
     * @return MensagemTicket
     */
    public function setAutor(Usuario $autor): self
    {
        $this->autor = $autor;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataHora(): \DateTime
    {
        return $this->dataHora;
    }

    /**
     * @return string
     */
    public function getTexto(): string
    {
        return $this->texto;
    }

    /**
     * @param mixed $texto
     * @return MensagemTicket
     */
    public function setTexto(string $texto): self
    {
        $this->texto = $texto;
        return $this;
    }
}
