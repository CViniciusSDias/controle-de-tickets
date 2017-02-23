<?php
namespace AppBundle\Service;

use AppBundle\Entity\{
    MensagemTicket, Ticket, Usuario
};
use AppBundle\Service\AcoesTicket\{
    AcaoAoAbrirTicket, AcaoAoFecharTicket, AcaoAoInteragir, AcaoAoReabrirTicket
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
    /** @var AcaoAoReabrirTicket[] $acoesAoFechar */
    private $acoesAoReabrir;

    /**
     * Inicializa os observers
     */
    public function __construct()
    {
        $this->acoesAoAbrir = [];
        $this->acoesAoInteragir = [];
        $this->acoesAoFechar = [];
        $this->acoesAoReabrir = [];
    }

    /**
     * Adiciona uma ação ao abrir um ticket
     *
     * @param AcaoAoAbrirTicket $acao
     * @return TicketManager
     */
    public function addAcaoAoAbrir(AcaoAoAbrirTicket $acao): self
    {
        $this->acoesAoAbrir[] = $acao;
        return $this;
    }

    /**
     * Adiciona uma ação ao interagir com um ticket
     *
     * @param AcaoAoInteragir $acao
     * @return TicketManager
     */
    public function addAcaoAoInteragir(AcaoAoInteragir $acao): self
    {
        $this->acoesAoInteragir[] = $acao;
        return $this;
    }

    /**
     * Adiciona uma ação ao fechar um ticket
     *
     * @param AcaoAoFecharTicket $acao
     * @return TicketManager
     */
    public function addAcaoAoFechar(AcaoAoFecharTicket $acao): self
    {
        $this->acoesAoFechar[] = $acao;
        return $this;
    }

    /**
     * Adiciona uma ação ao reabrir um ticket
     *
     * @param AcaoAoReabrirTicket $acao
     * @return TicketManager
     */
    public function addAcaoAoReabrir(AcaoAoReabrirTicket $acao): self
    {
        $this->acoesAoReabrir[] = $acao;
        return $this;
    }

    /**
     * Abre um novo ticket
     *
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

    /**
     * Adiciona uma interação ao ticket
     *
     * @param Ticket $ticket
     * @param string $textoMensagem
     * @param Usuario $usuarioLogado
     */
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

    /**
     * Fecha um ticket
     *
     * @param Ticket $ticket
     */
    public function fechar(Ticket $ticket): void
    {
        $ticket->fechar();

        foreach ($this->acoesAoFechar as $acao) {
            $acao->processaFechamento($ticket);
        }
    }

    /**
     * Reabre um ticket
     *
     * @param Ticket $ticket
     */
    public function reabrir(Ticket $ticket): void
    {
        $ticket->reabrir();

        foreach ($this->acoesAoReabrir as $acao) {
            $acao->processaReabertura($ticket);
        }
    }
}
