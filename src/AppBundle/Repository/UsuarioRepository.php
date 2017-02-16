<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Usuario;
use Doctrine\ORM\EntityRepository;

/**
 * Classe Repository de Usuários
 */
class UsuarioRepository extends EntityRepository
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

    public function findByEmail(string $email): ?Usuario
    {
        /** @var Usuario|null $usuario */
        $usuario = $this->findOneBy(['email' => $email]);

        return $usuario;
    }
}
