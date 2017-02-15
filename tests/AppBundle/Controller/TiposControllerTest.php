<?php
namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Tipo;
use Symfony\Bundle\FrameworkBundle\Client;

class TiposControllerTest extends AuthWebTestCase
{
    /** @var  Client */
    private $client;

    public function setUp()
    {
        $this->client = $this->createClientWithAuthentication();
    }

    public function testNaoLogado()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/categorias');

        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("/login")')->count());
    }

    public function testListaCategorias()
    {
        $crawler = $this->client->request('GET', '/categorias');
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Categorias")')->count());
    }

    public function testCategoriaComNomePequeno()
    {
        $crawler = $this->client->request('GET', '/categorias');
        $form = $crawler->selectButton('criar_categoria[salvar]')->form();
        $form['criar_categoria[nome]'] = 'a';
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.callout-danger:contains("O nome deve conter pelo menos 5 caracteres")')
                ->count()
        );
    }

    public function testInsereCategoria()
    {
        $crawler = $this->client->request('GET', '/categorias');
        $form = $crawler->selectButton('criar_categoria[salvar]')->form();
        $form['criar_categoria[nome]'] = 'Categoria';
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.callout-success:contains("Categoria adicionada com sucesso")')
                ->count()
        );
    }
}