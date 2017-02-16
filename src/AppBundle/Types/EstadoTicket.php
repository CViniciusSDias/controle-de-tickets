<?php
namespace AppBundle\Types;

use AppBundle\Entity\EstadoTicket\EstadoFactory;
use AppBundle\Entity\EstadoTicket\EstadoTicket as Estado;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Mapeia coluna `estado` da tabela ticket para enum EstadoTicket
 *
 * @package AppBundle\Types
 * @author Vinicius Dias
 */
class EstadoTicket extends Type
{

    /**
     * Retorna a definição SQL do campo
     *
     * @param array $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string 'INT DEFAULT 1'
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $sql = $platform->getIntegerTypeDeclarationSQL([]);
        $sql .= ' ' . $platform->getDefaultValueDeclarationSQL(['default' => 1, 'type' => 'Integer']);

        return $sql;
    }

    /**
     * Baseado no valor recebido do banco de dados, retorna um EstadoTicket
     *
     * @param int $value
     * @param AbstractPlatform $platform
     * @return Estado
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (new EstadoFactory())->getEstado(intval($value));
    }

    /**
     * @param Estado $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getValue();
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'estado_ticket';
    }
}