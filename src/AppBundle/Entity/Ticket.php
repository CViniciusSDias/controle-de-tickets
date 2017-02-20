<?php

namespace AppBundle\Entity;

use AppBundle\Service\TicketMessenger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\EstadoTicket\{
    Aberto, AguardandoAprovacao, EmAndamento, EstadoTicket
};

/**
 * Ticket
 *
 * @author Vinicius Dias
 * @package AppBundle\Entity
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
{
    use ModelTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     * @Assert\Length(min=8, minMessage="O título deve conter pelo menos 8 caracteres")
     */
    private $titulo;

    /**
     * @var EstadoTicket
     *
     * @ORM\Column(type="estado_ticket")
     */
    private $estado;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     * @Assert\LessThan(value=6, message="A prioridade deve ser entre 0 e 5")
     * @Assert\GreaterThanOrEqual(value=0, message="A prioridade deve ser entre 0 e 5")
     */
    private $prioridade;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dataHora;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
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
     * @ORM\ManyToOne(targetEntity="Tipo")
     */
    private $tipo;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="MensagemTicket", mappedBy="ticket", cascade={"all"})
     */
    private $mensagens;

    /**
     * Inicializa um ticket aberto com a data de hoje e prioridade = 3
     */
    public function __construct()
    {
        $this->estado = new Aberto();
        $this->prioridade = 3;
        $this->dataHora = new DateTime();
        $this->mensagens = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
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
        if (strlen($titulo) < 8) {
            throw new \InvalidArgumentException('O título deve conter pelo menos 8 caracteres');
        }

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
     * Set estado
     *
     * @param EstadoTicket $estado
     * @return Ticket
     */
    public function setEstado(EstadoTicket $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Retorna o estado do ticket
     *
     * @return EstadoTicket
     */
    public function getEstado(): EstadoTicket
    {
        return $this->estado;
    }

    /**
     * Retorna o estado como string
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->estado;
    }

    /**
     * Get aberto
     * @return boolean
     */
    public function getAberto(): bool
    {
        return $this->estado->ehAberto();
    }

    public function getCor(): string
    {
        return $this->estado->getCor();
    }

    /**
     * Set prioridade
     *
     * @param integer $prioridade
     * @return Ticket
     */
    public function setPrioridade(int $prioridade): self
    {
        if ($prioridade < 0 || $prioridade > 5) {
            throw new InvalidArgumentException('A prioridade deve ser entre 0 e 5');
        }

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
     * Set previsaoResposta a partir de uma string
     *
     * @param string $dataHora
     * @return Ticket
     */
    public function setResposta(string $dataHora): self
    {
        $this->previsaoResposta = DateTime::createFromFormat('d/m/Y H:i', $dataHora);
        return $this;
    }

    public function getResposta(): ?string
    {
        if (is_null($this->previsaoResposta)) {
            return null;
        }

        return $this->previsaoResposta->format('d/m/Y H:i:s');
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
        $this->estado = new EmAndamento();
        $this->addMensagem((new TicketMessenger())->getMensagemNovoResponsavel($this));

        return $this;
    }

    /**
     * Get atendenteResponsavel
     *
     * @return Usuario
     */
    public function getAtendenteResponsavel(): ?Usuario
    {
        return $this->atendenteResponsavel;
    }

    /**
     * Set categoria
     *
     * @param Tipo $tipo
     *
     * @return Ticket
     */
    public function setTipo(Tipo $tipo): self
    {
        $this->tipo = $tipo;
        $this->atendenteResponsavel = $tipo->getSupervisorResponsavel();

        return $this;
    }

    /**
     * Get categoria
     *
     * @return Tipo
     */
    public function getTipo(): ?Tipo
    {
        return $this->tipo;
    }

    /**
     * Tenta fechar o ticket atual
     *
     * @throws \BadMethodCallException
     */
    public function fechar(): void
    {
        $this->estado->fechar($this);
    }

    /**
     * Verifica se o ticket está aguardando aprovação do usuário
     *
     * @return bool
     */
    public function estaParaAprovacao(): bool
    {
        return $this->estado instanceof AguardandoAprovacao;
    }

    /**
     * Reabre um ticket, recolocando-o no estado EmAndamento
     */
    public function reabrir(): void
    {
        $this->estado = new EmAndamento();
        $mensagem = (new TicketMessenger())->getMensagemTicketReaberto($this);
        $this->addMensagem($mensagem);
    }

    /**
     * Verifica se um ticket pode ser gerenciado por determinado usuário.
     * Verdadeiro se o ticket estiver aberto e o usuário for pelo menos de suporte e tiver as devidas permissões sobre
     * ele ( caso seja de suporte, deve ser o responsável por ele, o que não é necessário caso seja supervisor ou adm )
     *
     * @param Usuario $usuario
     * @return bool
     */
    public function podeSerGerenciado(Usuario $usuario): bool
    {
        return $this->getAberto() && $usuario->podeVer($this) && $usuario->ehDeSuporte();
    }

    /**
     * Adiciona uma mensagem (interação) ao ticket
     *
     * @param MensagemTicket $mensagem
     */
    public function addMensagem(MensagemTicket $mensagem): void
    {
        $this->mensagens->add($mensagem);
        $mensagem->setTicket($this);
    }

    public function getMensagens(): Collection
    {
        return $this->mensagens;
    }
}
