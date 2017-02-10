<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Tipo;
use Doctrine\Common\DataFixtures\{AbstractFixture, OrderedFixtureInterface};
use Doctrine\Common\Persistence\ObjectManager;

/**
 * DataFixture de categoria
 *
 * @package AppBundle\DataFixtures\ORM
 * @author Vinicius Dias
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Insere uma categoria com nome 'Testes' no banco de dados
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $categoria = new Tipo();
        $categoria->setNome('Testes');
        $manager->persist($categoria);
        $manager->flush();

        $this->setReference('categoria', $categoria);
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
}
