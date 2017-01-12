<?php
namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Link;

class TicketsControllerTest extends WebTestCase
{
    /** @var Client */
    private static $cliente;

    public static function setUpBeforeClass()
    {
        static::$cliente = static::createClient();
        $login = new LoginControllerTest();
        $login->login(static::$cliente);
    }

    public function testAbrirTicketComTituloComMenosDe8Caracteres()
    {
        $crawler = static::$cliente->request('GET', '/tickets/novo');
        $form = $crawler->selectButton('criar_ticket[salvar]')->form();
        $form['criar_ticket[titulo]'] = 'titulo';
        $form['criar_ticket[categoria]'] = 1;
        $crawler = static::$cliente->submit($form);

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
        $crawler = static::$cliente->request('GET', '/tickets');
        $link = $crawler->filter('tbody tr:first-child a')->link();

        $crawler = static::$cliente->request('GET', $link->getUri());
        $form = $crawler->selectButton('form[salvar]')->form();

        $form['form[prioridade]'] = $prioridade;
        $crawler = static::$cliente->submit($form);


        $this->assertGreaterThan(
            0,
            $crawler->filter('div.callout-danger:contains("A prioridade deve ser entre 0 e 5")')
                ->count()
        );
    }
}