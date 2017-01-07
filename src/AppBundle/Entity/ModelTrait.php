<?php
namespace AppBundle\Entity;

use DomainException;

trait ModelTrait
{
    public function __set($propriedade, $valor)
    {
        $metodo = 'set' . ucfirst($propriedade);
        if (method_exists($this, $metodo))
            return $this->$metodo($valor);

        throw new DomainException('Propriedade ' . __CLASS__ . "::$propriedade inválida");
    }

    public function __get($propriedade)
    {
        $metodo = 'get' . ucfirst($propriedade);
        if (method_exists($this, $metodo))
            return $this->$metodo();

        throw new DomainException('Propriedade' . __CLASS__ . "::$propriedade inválida");
    }
}