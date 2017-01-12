<?php
namespace tests\AppBundle\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UsuarioRepositoryTest extends KernelTestCase
{
    /** @var \AppBundle\Repository\UsuarioRepository */
    private $repo;

    protected function setUp()
    {
        self::bootKernel();

        $em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repo = $em->getRepository('AppBundle:Usuario');
    }

    public function testUsuariosDevemVirOrdenados()
    {
        $usuarios = $this->repo->findBy([]);
        $numeroUsuarios = count($usuarios);
        // Deve existir pelo menos um usuário cadastrado, sempre
        $this->assertGreaterThan(0, $numeroUsuarios);

        for ($i = 1; $i < $numeroUsuarios; $i++) {
            $usuarioAtual = $usuarios[$i];
            $usuarioAnterior = $usuarios[$i - 1];

            if ($usuarioAtual->getDataCadastro()->getTimestamp() > $usuarioAnterior->getDataCadastro()->getTimestamp()) {
                $this->fail();
            }
        }

        $this->assertTrue(true);
    }
}