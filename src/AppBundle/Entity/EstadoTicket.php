<?php
namespace AppBundle\Entity;

use MyCLabs\Enum\Enum;

class EstadoTicket extends Enum
{
    const ABERTO = 1;
    const EM_ANDAMENTO = 2;
    const AGUARDANDO_APROVACAO = 3;
    const FECHADO = 4;

    public function __toString()
    {
        $valor = $this->getKey();
        $valor = str_replace('_', ' ', $valor);
        $valor = mb_strtolower($valor);
        $valor = ucwords($valor);

        return $valor;
    }
}