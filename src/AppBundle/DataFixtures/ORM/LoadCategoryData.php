<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Tipo;
use Doctrine\Common\DataFixtures\{AbstractFixture, OrderedFixtureInterface};
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\{ContainerAwareInterface,ContainerInterface};

/**
 * DataFixture de tipos (apenas para testes)
 *
 * @package AppBundle\DataFixtures\ORM
 * @author Vinicius Dias
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface $container */
    private $container;

    /**
     * Insere uma categoria com nome 'Testes' no banco de dados
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        if ($this->container->getParameter('kernel.environment') === 'test') {
            $categoria = new Tipo();
            $categoria->setNome('Testes');
            $manager->persist($categoria);
            $manager->flush();

            $this->setReference('categoria', $categoria);
        }
    }

    /**
     * Define a ordem em que a fixture deve ser executada
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
