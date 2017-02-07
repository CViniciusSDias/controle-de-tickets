<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\{Usuario, Ticket};
use PHPUnit\Framework\TestCase;

class UsuarioTest extends TestCase
{
    /** @var Usuario */
    private $usuario;

    public function setUp()
    {
        $this->usuario = new Usuario();
        $this->usuario->setNome('Teste');
    }

    public function testAtendenteResponsavelPodeVerTicket()
    {
        $ticket = new Ticket();
        $ticket->setAtendenteResponsavel($this->usuario);

        $this->assertTrue($this->usuario->podeVer($ticket));
    }

    public function testUsuarioCriadorPodeVerTicket()
    {
        $ticket = new Ticket();
        $ticket->setUsuarioCriador($this->usuario);

        $this->assertTrue($this->usuario->podeVer($ticket));
    }

    public function testUsuarioNaoPodeVerTicket()
    {
        $ticket = new Ticket();
        $ticket->setUsuarioCriador(new Usuario());

        $this->assertFalse($this->usuario->podeVer(($ticket)));
    }
}