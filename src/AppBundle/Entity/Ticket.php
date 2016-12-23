<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

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
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=255, nullable=true)
     */
    private $descricao;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="prioridade", type="smallint")
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="usuario_criador", referencedColumnName="id")
     */
    private $usuarioCriador;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="atendente_responsavel", referencedColumnName="id", nullable=true)
     */
    private $atendenteResponsavel;

    public function __construct()
    {
        $this->status = 'aberto';
        $this->prioridade = 3;
        $this->dataHora = new DateTime();
    }
}