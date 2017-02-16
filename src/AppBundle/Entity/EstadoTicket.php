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

    public function getCor(): string
    {
        $cor = '';
        $estado = $this->getValue();
        switch ($estado) {
            case static::ABERTO:
                $cor = 'red';
                break;
            case static::EM_ANDAMENTO:
                $cor = 'orange';
                break;
            case static::AGUARDANDO_APROVACAO:
                $cor = 'blue';
                break;
            case static::FECHADO:
                $cor = 'green';
                break;
        }

        return $cor;
    }

    public function fechar(Ticket $ticket): void
    {
        $aguardando = self::AGUARDANDO_APROVACAO();
        if ($ticket->getEstado()->equals($aguardando)) {
            $ticket->setEstado(self::FECHADO());
            return;
        }

        $ticket->setEstado($aguardando);
    }

    public function ehAberto(): bool
    {
        return in_array($this->getValue(), [self::ABERTO, self::EM_ANDAMENTO]);
    }
}
