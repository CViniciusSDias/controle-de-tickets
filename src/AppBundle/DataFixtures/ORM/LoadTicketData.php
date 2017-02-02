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
        $ticket = new Ticket();
        $ticket->setAberto(true)
            ->setCategoria($this->getReference('categoria'))
            ->setDataHora(new \DateTime())
            ->setDescricao('Teste')
            ->setTitulo('Ticket Teste')
            ->setUsuarioCriador($this->getReference('usuario'));
        $manager->persist($ticket);
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