<?php
namespace AppBundle\Entity;

use DomainException;

/**
 * Trait que possibilita que os getters e setters sejam chamados como forma de propriedades públicas
 *
 * @author Vinicius Dias
 * @package AppBundle\Entity
 */
trait ModelTrait
{
    public function __set($propriedade, $valor)
    {
        $metodo = 'set' . ucfirst($propriedade);
        if (method_exists($this, $metodo)) {
            return $this->$metodo($valor);
        }

        throw new DomainException('Propriedade ' . __CLASS__ . "::$propriedade inválida");
    }

    public function __get($propriedade)
    {
        $metodo = 'get' . ucfirst($propriedade);
        if (method_exists($this, $metodo)) {
            return $this->$metodo();
        }

        throw new DomainException('Propriedade' . __CLASS__ . "::$propriedade inválida");
    }
}
