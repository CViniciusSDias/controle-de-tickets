<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Tipo;
use AppBundle\Entity\Ticket;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * DataFixture de Tickets
 *
 * @package AppBundle\DataFixtures\ORM
 * @author Vinicius Dias
 */
class LoadTicketData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Insere um ticket (aberto) no sistema para testes
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var Tipo $categoria */
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
     * Define a ordem em que a fixture deve ser executada
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
