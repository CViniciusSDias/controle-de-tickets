<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\EstadoTicket\{Aberto, AguardandoAprovacao, EmAndamento, Fechado};
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Tipo;
use AppBundle\Entity\Usuario;
use PHPUnit\Framework\TestCase;

class TicketTest extends TestCase
{
    private $usuario;

    public function setUp()
    {
        $this->usuario = new Usuario();
        $this->usuario->setNome('Usuário');
    }

    public function testNovoTicketDeveSerAberto()
    {
        $ticket = new Ticket();
        static::assertEquals(new Aberto(), $ticket->getEstado());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testTicketAbertoNaoPodeSerFechado()
    {
        $ticket = new Ticket();
        $ticket->setUsuarioCriador($this->usuario);
        $ticket->fechar();
    }

    public function testTicketEmAndamentoDeveSerFechadoParaAguardandoAprovacao()
    {
        $ticket = new Ticket();
        $ticket->setUsuarioCriador($this->usuario);
        $ticket->setAtendenteResponsavel($this->usuario);
        $ticket->setEstado(new EmAndamento());
        $ticket->fechar();
        static::assertEquals(new AguardandoAprovacao(), $ticket->getEstado());
    }

    public function testTicketAguardandoAprovacaoDeveSerFechadoParaFechado()
    {
        $ticket = new Ticket();
        $ticket->setUsuarioCriador($this->usuario);
        $ticket->setAtendenteResponsavel($this->usuario);
        $ticket->setEstado(new EmAndamento());
        $ticket->fechar();
        $ticket->fechar();
        static::assertAttributeEquals(new Fechado(), 'estado', $ticket);

    }

    public function testTicketComResponsavelDeveFicarEmAndamento()
    {
        $ticket = new Ticket();
        $ticket->setAtendenteResponsavel($this->usuario);
        static::assertEquals(new EmAndamento(), $ticket->getEstado());
    }

    public function testTicketAssociadoAUmTipoFicaSobResponsabilidadeDeSeuSupervisor()
    {
        $tipo = new Tipo();
        $tipo->setSupervisorResponsavel(new Usuario());
        $ticket = new Ticket();
        $ticket->setTipo($tipo);

        static::assertEquals($tipo->getSupervisorResponsavel(), $ticket->getAtendenteResponsavel());
    }

    public function testSetDataRespostaEmString()
    {
        $hoje = new \DateTime('today 12pm');
        $dataString = $hoje->format('d/m/Y H:i');
        $ticket = new Ticket();
        $ticket->setResposta($dataString);

        static::assertEquals($hoje, $ticket->getPrevisaoResposta());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDefinePrioridadeInvalida()
    {
        $ticket = new Ticket();
        $ticket->setPrioridade(10);
    }

    public function testDefinePrioridadeValida()
    {
        $ticket = new Ticket();
        $ticket->setPrioridade(1);

        static::assertEquals(1, $ticket->getPrioridade());
    }

    public function testGetRespostaFormatada()
    {
        $hoje = new \DateTime();
        $ticket = new Ticket();
        $ticket->setPrevisaoResposta($hoje);

        static::assertEquals($hoje->format('d/m/Y H:i:s'), $ticket->getResposta());
    }
}
