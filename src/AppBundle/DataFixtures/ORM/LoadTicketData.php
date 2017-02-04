<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Categoria;
use AppBundle\Entity\Ticket;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTicketData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var Categoria $categoria */
        $categoria = $this->getReference('categoria');
        $ticketAberto = new Ticket();
        $ticketAberto->setAberto(true)
            ->setCategoria($categoria)
            ->setDataHora(new \DateTime())
            ->setDescricao('Teste')
            ->setTitulo('Ticket Teste')
            ->setUsuarioCriador($this->getReference('usuario'));
        $manager->persist($ticketAberto);
        $ticketFechado = clone $ticketAberto;
        $ticketFechado->setAberto(false);
        $manager->persist($ticketFechado);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
