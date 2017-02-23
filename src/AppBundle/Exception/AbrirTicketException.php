<?php
namespace AppBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class AbrirTicketException extends \RuntimeException
{
    /** @var  ConstraintViolationListInterface $erros */
    private $erros;

    /**
     * @return ConstraintViolationListInterface
     */
    public function getErros(): ConstraintViolationListInterface
    {
        return $this->erros;
    }

    /**
     * @param ConstraintViolationListInterface $erros
     */
    public function setErros(ConstraintViolationListInterface $erros)
    {
        $this->erros = $erros;
    }
}
