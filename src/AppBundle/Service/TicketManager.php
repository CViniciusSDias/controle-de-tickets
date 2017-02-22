<?php
namespace AppBundle\Service;

use AppBundle\Entity\{
    MensagemTicket, Ticket, Usuario
};
use AppBundle\Service\AcoesTicket\{
    AcaoAoAbrirTicket, AcaoAoFecharTicket, AcaoAoInteragir
};
use AppBundle\Exception\AbrirTicketException;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TicketManager
{
    /** @var AcaoAoAbrirTicket[] $acoesAoAbrir */
    private $acoesAoAbrir;
    /** @var AcaoAoInteragir[] $acoesAoInteragir */
    private $acoesAoInteragir;
    /** @var AcaoAoFecharTicket[] $acoesAoFechar */
    private $acoesAoFechar;

    public function __construct()
    {
        $this->acoesAoAbrir = [];
        $this->acoesAoInteragir = [];
        $this->acoesAoFechar = [];
    }

    public function addAcaoAoAbrir(AcaoAoAbrirTicket $acao): self
    {
        $this->acoesAoAbrir[] = $acao;
        return $this;
    }

    public function addAcaoAoInteragir(AcaoAoInteragir $acao): self
    {
        $this->acoesAoInteragir[] = $acao;
        return $this;
    }

    public function addAcaoAoFechar($acao): self
    {
        $this->acoesAoFechar[] = $acao;
        return $this;
    }

    /**
     * @param Form $form
     * @param Usuario $usuarioLogado
     * @param ValidatorInterface $validator
     * @throws AbrirTicketException
     */
    public function abrir(Form $form, Usuario $usuarioLogado, ValidatorInterface $validator): void
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
    }

    public function interagir(Ticket $ticket, string $textoMensagem, Usuario $usuarioLogado)
    {
        $mensagem = new MensagemTicket();
        $mensagem
            ->setAutor($usuarioLogado)
            ->setTexto($textoMensagem);
        $ticket->addMensagem($mensagem);

        foreach ($this->acoesAoInteragir as $acao) {
            $acao->processaInteracao($ticket);
        }
    }

    public function fechar(Ticket $ticket): void
    {
        $ticket->fechar();

        foreach ($this->acoesAoFechar as $acao) {
            $acao->processaFechamento($ticket);
        }
    }
}
