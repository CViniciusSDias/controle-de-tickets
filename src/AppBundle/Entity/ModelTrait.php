<?php
namespace AppBundle\Entity;

use DomainException;

trait ModelTrait
{
    public function __set($propriedade, $valor)
    {
        if ($propriedade === 'id')
            throw new DomainException('ID é de somente leitura');

        $metodo = 'set' . ucfirst($propriedade);
        if (method_exists($this, $metodo))
            return $this->$metodo($valor);

        if (!property_exists($this, $propriedade))
            throw new DomainException('Propriedade inválida');

        $this->$propriedade = $valor;
    }

    public function __get($propriedade)
    {
        $metodo = 'get' . ucfirst($propriedade);
        if (method_exists($this, $metodo))
            return $this->$metodo();

        if (property_exists($this, $propriedade))
            return $this->$propriedade;

        throw new DomainException('Propriedade inválida');
    }

    public function __call(string $metodo, array $args)
    {
        $propriedade = lcfirst(str_replace('get', '', $metodo));
        return $this->$propriedade;
    }
}