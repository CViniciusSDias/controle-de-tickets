<?php
namespace AppBundle\Entity\EstadoTicket;

use AppBundle\Entity\Ticket;

interface EstadoTicket
{
    /**
     * Cor que representa o ticket na listagem
     *
     * @return string
     */
    public function getCor(): string;

    /**
     * Nome do estado
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Indica se o ticket ainda está aberto
     *
     * @return bool
     */
    public function ehAberto(): bool;

    /**
     * @param Ticket $ticket Ticket a ser fechado
     * @throws \BadMethodCallException Caso o ticket já esteja fechado
     */
    public function fechar(Ticket $ticket): void;

    /**
     * Retorna o valor correspondente no banco de dados
     *
     * @return int
     */
    public function getDbValue(): int;
}
