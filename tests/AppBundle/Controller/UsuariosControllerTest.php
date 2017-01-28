<?php
namespace Tests\AppBundle\Controller;

class UsuariosControllerTest extends AuthWebTestCase
{
    private $cliente;

    public function setUp()
    {
        $this->cliente = $this->createClientWithAuthentication('main');
    }

    public function testListaUsuarios()
    {
        $crawler = $this->cliente->request('GET', '/usuarios');

        $this->assertGreaterThan(
            0,
            $crawler->filter('div#tabela-usuarios table tbody tr')->count()
        );
    }

    public function testEmailInvalido()
    {
        $crawler = $this->cliente->request('GET', '/usuarios/novo');

        $form = $crawler->selectButton('criar_usuario[salvar]')->form();
        $form['criar_usuario[nome]']  = 'Nome usuário';
        $form['criar_usuario[email]'] = 'email inválido';
        $form['criar_usuario[senha]'] = 'Senha usuário';
        $form['criar_usuario[tipo]']  = 'ROLE_USER';

        $crawler = $this->cliente->submit($form);
        $this->assertEquals(
            1,
            $crawler->filter('div.callout-danger:contains("E-mail inválido")')
                ->count()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTipoInvalido()
    {
        $crawler = $this->cliente->request('GET', '/usuarios/novo');

        $form = $crawler->selectButton('criar_usuario[salvar]')->form();
        $form['criar_usuario[nome]']  = 'Nome usuário';
        $form['criar_usuario[email]'] = 'email@valido.com';
        $form['criar_usuario[senha]'] = 'Senha usuário';
        $form['criar_usuario[tipo]']  = 'tipo inválido';

        // Deve lançar uma exceção, pois o tipo é inválido
        $this->cliente->submit($form);
    }
}
