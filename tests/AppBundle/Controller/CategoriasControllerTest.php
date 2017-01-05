<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use tests\AppBundle\Controller\LoginControllerTest;

class CategoriasControllerTest extends WebTestCase
{
    /** @var  Client */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testNaoLogado()
    {
        $this->client  = static::createClient();
        $crawler = $this->client->request('GET', '/categorias');

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("/login")')->count());
    }

    public function testListaCategorias()
    {
        $this->login();
        $crawler = $this->client->request('GET', '/categorias');
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Categorias")')->count());
    }

    public function testCategoriaComNomePequeno()
    {
        $this->login();
        $crawler = $this->client->request('GET', '/categorias');
        $form = $crawler->selectButton('form[salvar]')->form();
        $form['form[nome]'] = 'a';
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.callout-danger:contains("O nome deve conter pelo menos 5 caracteres")')->count()
        );
    }

    private function login()
    {
        $loginTest = new LoginControllerTest();
        $loginTest->login($this->client);
    }
}
