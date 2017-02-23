<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\EstadoTicket\Fechado;
use AppBundle\Entity\MensagemTicket;
use AppBundle\Entity\Tipo;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Usuario;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\{ContainerAwareInterface,ContainerInterface};

/**
 * DataFixture de Tickets (apenas para testes)
 *
 * @package AppBundle\DataFixtures\ORM
 * @author Vinicius Dias
 */
class LoadTicketData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /** @var  ContainerInterface $container */
    private $container;

    /**
     * Insere um ticket (aberto) no sistema para testes
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        if ($this->container->getParameter('kernel.environment') === 'test') {
            /** @var Usuario $usuario */
            $usuario = $this->getReference('usuario');
            /** @var Tipo $categoria */
            $categoria = $this->getReference('categoria');
            $ticketAberto = new Ticket();
            $ticketAberto
                ->setTipo($categoria)
                ->setDataHora(new \DateTime())
                ->setTitulo('Ticket Teste')
                ->setUsuarioCriador($usuario);
            $mensagem = new MensagemTicket();
            $mensagem
                ->setAutor($usuario)
                ->setTexto('Mensagem Teste');
            $ticketAberto->addMensagem($mensagem);
            $manager->persist($ticketAberto);

            $ticketFechado = clone $ticketAberto;
            $ticketFechado->setEstado(new Fechado());
            $manager->persist($ticketFechado);

            $manager->flush();
        }
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
