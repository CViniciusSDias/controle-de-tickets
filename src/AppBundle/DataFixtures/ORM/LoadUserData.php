<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Usuario;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\{ContainerAwareInterface,ContainerInterface};

/**
 * DataFixture de Usuário
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /** @var  ContainerInterface */
    private $container;

    /**
     * Insere um usuário administrador no sistema com email 'email@example.com' e senha 'admin'
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $usuario = new Usuario();
        $usuario
            ->setNome('Administrador')
            ->setTipo('ROLE_SUPER_ADMIN')
            ->setDataCadastro(new \DateTime())
            ->setSenha('admin')
            ->setEmail('email@example.com');
        $this->container->get('app.redefinidor_senha')->codificar($usuario);
        $manager->persist($usuario);
        $manager->flush();

        $this->addReference('usuario', $usuario);
    }

    /**
     * Define o container. Container se faz necessário para acessar o serviço app.redefinidor_senha
     *
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Define a ordem em que a fixture deve ser executada
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
