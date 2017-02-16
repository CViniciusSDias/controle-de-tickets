<?php
namespace AppBundle\Entity\EstadoTicket;

class EstadoFactory
{
    public function getEstado(int $valorInt): EstadoTicket
    {
        $estado = null;

        switch ($valorInt) {
            case 1:
                $estado = new Aberto();
                break;
            case 2:
                $estado = new EmAndamento();
                break;
            case 3:
                $estado = new AguardandoAprovacao();
                break;
            case 4:
                $estado = new Fechado();
                break;
        }

        return $estado;
    }
}