<?php
namespace AppBundle\Service;

use AppBundle\Entity\{
    MensagemTicket, Ticket, Usuario
};
use AppBundle\Exception\AbrirTicketException;
use AppBundle\Service\AcoesTicket\AcaoAoAbrirTicket;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TicketManager
{
    /** @var AcaoAoAbrirTicket[] $acoesAoAbrir */
    private $acoesAoAbrir;

    public function __construct()
    {
        $this->acoesAoAbrir = [];
    }

    public function addAcaoAoAbrir(AcaoAoAbrirTicket $acao)
    {
        $this->acoesAoAbrir[] = $acao;
        return $this;
    }

    /**
     * @param Form $form
     * @param Usuario $usuarioLogado
     * @param ValidatorInterface $validator
     * @throws AbrirTicketException
     * @return bool
     */
    public function abrir(Form $form, Usuario $usuarioLogado, ValidatorInterface $validator): bool
    {
        /** @var Ticket $ticket */
        $ticket = $form->getData();
        $mensagem = new MensagemTicket();
        $mensagem
            ->setAutor($usuarioLogado)
            ->setTexto($form['descricao']->getData());
        $ticket->addMensagem($mensagem);
        $ticket->setUsuarioCriador($usuarioLogado);
        $erros = $validator->validate($ticket);

        if (count($erros) > 0) {
            $exception = new AbrirTicketException();
            $exception->setErros($erros);

            throw $exception;
        }

        foreach ($this->acoesAoAbrir as $acao) {
            $acao->processaAbertura($ticket);
        }
        return true;
    }
}
