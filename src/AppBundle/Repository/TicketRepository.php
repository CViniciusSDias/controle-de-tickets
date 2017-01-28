<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Usuario;

/**
 * Classe Repository de Tickets.
 */
class TicketRepository extends \Doctrine\ORM\EntityRepository
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
}
