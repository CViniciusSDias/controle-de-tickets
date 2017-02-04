<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Categoria;
use Doctrine\Common\DataFixtures\{AbstractFixture, OrderedFixtureInterface};
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $categoria = new Categoria();
        $categoria->setNome('Testes');
        $manager->persist($categoria);
        $manager->flush();

        $this->setReference('categoria', $categoria);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
