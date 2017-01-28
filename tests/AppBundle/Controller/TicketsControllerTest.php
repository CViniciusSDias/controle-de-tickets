<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;

class TicketsControllerTest extends AuthWebTestCase
{
    /** @var Client */
    private $cliente;

    public function setUp()
    {
        $this->cliente = $this->createClientWithAuthentication();
    }

    public function testAbrirTicketComTituloComMenosDe8Caracteres()
    {
        $crawler = $this->cliente->request('GET', '/tickets/novo');
        $form = $crawler->selectButton('criar_ticket[salvar]')->form();
        $form['criar_ticket[titulo]'] = 'titulo';
        $form['criar_ticket[categoria]'] = 1;
        $crawler = $this->cliente->submit($form);

        $this->assertGreaterThan(
            0,
            $crawler->filter('div.callout-danger:contains("O título deve conter pelo menos 8 caracteres")')
                ->count()
        );
    }

    public function testPrioridadeMenorQue0()
    {
        $this->preencheForm(-1);
    }

    public function testPrioridadeMaiorQue5()
    {
        $this->preencheForm(6);
    }

    public function preencheForm(int $prioridade)
    {
        $crawler = $this->cliente->request('GET', '/tickets');
        $link = $crawler->filter('tbody tr:first-child a')->link();

        $crawler = $this->cliente->request('GET', $link->getUri());
        $form = $crawler->selectButton('gerenciar_ticket[salvar]')->form();

        $form['gerenciar_ticket[prioridade]'] = $prioridade;
        $crawler = $this->cliente->submit($form);


        $this->assertGreaterThan(
            0,
            $crawler->filter('div.callout-danger:contains("A prioridade deve ser entre 0 e 5")')
                ->count()
        );
    }

    public function testListarTicketsAbertos()
    {
        $crawler = $this->cliente->request('GET', '/tickets/abertos');
        $spans = $crawler->filter('tbody tr td > span.label');

        foreach ($spans as $spanLabel) {
            $this->assertEquals('aberto', trim($spanLabel->textContent));
        }
    }

    public function testListarTicketsFechados()
    {
        $crawler = $this->cliente->request('GET', '/tickets/fechados');
        $spans = $crawler->filter('tbody tr td > span.label');

        foreach ($spans as $spanLabel) {
            $this->assertEquals('fechado', trim($spanLabel->textContent));
        }
    }
}