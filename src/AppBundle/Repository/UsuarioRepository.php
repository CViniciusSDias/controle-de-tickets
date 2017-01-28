<?php

namespace AppBundle\Repository;

/**
 * Classe Repository de Usuários
 */
class UsuarioRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Busca os usuários ordenados (por padrão) pelo campo dataCadastro de forma decrescente.
     *
     * @inheritdoc
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (is_null($orderBy)) {
            $orderBy = ['dataCadastro' => 'desc'];
        }
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}
