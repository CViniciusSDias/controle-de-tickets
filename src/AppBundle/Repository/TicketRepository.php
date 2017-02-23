<?php
namespace AppBundle\Repository;

use AppBundle\Entity\{
    EstadoTicket\Aberto, EstadoTicket\Fechado, Ticket, Usuario
};
use AppBundle\Service\AcoesTicket\{
    AcaoAoAbrirTicket, AcaoAoFecharTicket, AcaoAoInteragir, AcaoAoReabrirTicket
};
use Doctrine\ORM\EntityRepository;

/**
 * Classe Repository de Tickets.
 */
class TicketRepository extends EntityRepository implements AcaoAoAbrirTicket, AcaoAoInteragir, AcaoAoFecharTicket, AcaoAoReabrirTicket
{
    /**
     * Busca os tickets ordenados (por padrão) pelo campo dataHora de forma descendente
     *
     * @inheritdoc
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (is_null($orderBy)) {
            $orderBy = ['dataHora' => 'desc'];
        }
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Busca o número de tickets abertos por determinado usuário
     *
     * @param Usuario $usuario
     * @return int
     */
    public function ticketsAbertosPor(Usuario $usuario): int
    {
        return intval($this->getEntityManager()
            ->createQuery('SELECT COUNT(t) FROM AppBundle:Ticket t WHERE t.usuarioCriador = ?1')
            ->setParameter(1, $usuario)
            ->getResult()[0][1]);
    }

    /**
     * Busca o número de tickets sob responsabilidade de determinado atendente
     *
     * @param Usuario $usuario
     * @return int
     */
    public function ticketsSobResponsabilidade(Usuario $usuario): int
    {
        return intval($this->getEntityManager()
            ->createQuery('SELECT COUNT(t) FROM AppBundle:Ticket t WHERE t.atendenteResponsavel = ?1')
            ->setParameter(1, $usuario)
            ->getResult()[0][1]);
    }

    /**
     * Busca os tickets com o estado = 1 (aberto)
     *
     * @return array
     */
    public function findAbertos(): array
    {
        return $this->findBy(['estado' => new Aberto()]);
    }

    /**
     * Busca os tickets com o estado = 4 (fechado)
     *
     * @return array
     */
    public function findFechados(): array
    {
        return $this->findBy(['estado' => new Fechado()]);
    }

    /**
     * Busca os tickets sob responsabilidade do usuário informado
     *
     * @param Usuario $usuario
     * @return array
     */
    public function findByAtendente(Usuario $usuario): array
    {
        return $this->findBy(['atendenteResponsavel' => $usuario]);
    }

    /**
     * Busca os tickets abertos pelo usuário informado
     *
     * @param Usuario $usuario
     * @return array
     */
    public function findByCriador(Usuario $usuario): array
    {
        return $this->findBy(['usuarioCriador' => $usuario]);
    }

    public function processaAbertura(Ticket $ticket): void
    {
        $this->flush($ticket);
    }

    public function processaInteracao(Ticket $ticket): void
    {
        $this->getEntityManager()->flush();
    }

    public function processaFechamento(Ticket $ticket): void
    {
        $this->flush($ticket);
    }

    public function processaReabertura(Ticket $ticket): void
    {
        $this->flush($ticket);
    }

    /**
     * @param Ticket $ticket
     */
    private function flush(Ticket $ticket): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($ticket);
        $entityManager->flush();
    }
}
