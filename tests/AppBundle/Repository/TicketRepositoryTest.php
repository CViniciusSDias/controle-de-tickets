<?php
namespace tests\AppBundle\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TicketRepositoryTest extends KernelTestCase
{
    private $em;
    /** @var \AppBundle\Repository\TicketRepository */
    private $repo;

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repo = $this->em->getRepository('AppBundle:Ticket');
    }

    public function testTicketsDevemVirOrdenados()
    {
        $tickets = $this->repo->findBy([]);
        $numeroTickets = count($tickets);

        for ($i = 1; $i < $numeroTickets; $i++) {
            $ticket = $tickets[$i];
            $ticketAnterior = $tickets[$i - 1];

            // Data do ticket anterior deve ser maior que a do ticket atual, pois estão ordenados
            if ($ticket->getDataHora()->getTimestamp() > $ticketAnterior->getDataHora()->getTimestamp()) {
                $this->fail();
            }
        }

        $this->assertTrue(true);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}