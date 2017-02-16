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
        $crawler = $client->request('GET', '/tipos');

        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("/login")')->count());
    }

    public function testListaCategorias()
    {
        $crawler = $this->client->request('GET', '/tipos');
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Categorias")')->count());
    }

    public function testCategoriaComNomePequeno()
    {
        $crawler = $this->client->request('GET', '/tipos/novo');
        $form = $crawler->selectButton('criar_tipo[salvar]')->form();
        $form['criar_tipo[nome]'] = 'a';
        $form['criar_tipo[supervisorResponsavel]'] = 1;
        $crawler = $this->client->submit($form);
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.callout-danger:contains("O nome deve conter pelo menos 5 caracteres")')
                ->count()
        );
    }

    public function testInsereTipo()
    {
        $crawler = $this->client->request('GET', '/tipos/novo');
        $form = $crawler->selectButton('criar_tipo[salvar]')->form();
        $form['criar_tipo[nome]'] = 'Categoria';
        $form['criar_tipo[supervisorResponsavel]'] = 1;
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.callout-success:contains("Tipo adicionado com sucesso")')
                ->count()
        );
    }
}
