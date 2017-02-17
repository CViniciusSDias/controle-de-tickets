<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\EstadoTicket\{Aberto, AguardandoAprovacao, EmAndamento, Fechado};
use AppBundle\Entity\Ticket;
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
        static::assertAttributeEquals(new Aberto(), 'estado', $ticket);
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
        static::assertAttributeEquals(new AguardandoAprovacao(), 'estado', $ticket);
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
        static::assertAttributeEquals(new EmAndamento(), 'estado', $ticket);
    }
}
