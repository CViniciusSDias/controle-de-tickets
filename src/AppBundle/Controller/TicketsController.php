<?php
namespace AppBundle\Controller;

use AppBundle\Entity\{
    MensagemTicket, Ticket, Usuario
};
use AppBundle\Exception\AbrirTicketException;
use AppBundle\Forms\{
    CriarTicketType, GerenciarTicketType
};
use AppBundle\Service\TicketManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\{
    Request, Response
};
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Controller para Tickets
 *
 * @author Vinicius Dias
 * @package AppBundle\Controller
 */
class TicketsController extends Controller
{
    /**
     * Exibe o formulário de abertura de ticket e cria um novo ticket
     * com as informações enviadas por este formulário.
     *
     * @Route("/tickets/novo", name="cadastrar_ticket")
     * @param Request $request
     * @return Response
     */
    public function cadastrarAction(Request $request): Response
    {
        $form = $this->createForm(CriarTicketType::class, new Ticket());

        try {
            $form->handleRequest($request);

            /* Caso seja uma requisição post, e o formulário já tenha sido enviado */
            if ($form->isSubmitted()) {
                $ticketManager = new TicketManager();
                $ticketManager
                    ->addAcaoAoAbrir($this->get('app.ticket_repository'))
                    ->addAcaoAoAbrir($this->get('app.email_ticket_aberto'));
                $ticketManager->abrir($form, $this->getUser(), $this->get('validator'));

                $this->addFlash('success', 'Ticket cadastrado com sucesso');
                return $this->redirect($request->getUri());
            }
        } catch (\InvalidArgumentException $e) {
            $this->addErrosAoEscopoFlash([$e]);
        } catch (AbrirTicketException $e) {
            $this->addErrosAoEscopoFlash($e->getErros());
        }

        return $this->render('tickets/cadastrar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Exibe todos os tickets ordenados por data
     *
     * @Route("/tickets", name="listar_tickets")
     * @return Response
     */
    public function listarAction(): Response
    {
        $tickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')
            ->findBy([]);
        return $this->render('tickets/listar.html.twig', ['tickets' => $tickets]);
    }

    /**
     * Exibe os tickets abertos ordenados por data
     *
     * @Route("/tickets/abertos", name="listar_tickets_abertos")
     * @return Response
     */
    public function listarAbertosAction(): Response
    {
        $ticketsRepo = $this->getDoctrine()->getRepository('AppBundle:Ticket');
        $tickets = $ticketsRepo->findAbertos();

        return $this->render('tickets/listar.html.twig', ['tickets' => $tickets]);
    }

    /**
     * Exibe os tickets fechados ordenados por data
     *
     * @Route("/tickets/fechados", name="listar_tickets_fechados")
     * @return Response
     */
    public function listarFechadosAction(): Response
    {
        $ticketsRepo = $this->getDoctrine()->getRepository('AppBundle:Ticket');
        $tickets = $ticketsRepo->findFechados();

        return $this->render('tickets/listar.html.twig', ['tickets' => $tickets]);
    }

    /**
     * Exibe os tickets sob responsabilidade do usuário logado ordenados por data
     *
     * @Route("/tickets/meus", name="listar_tickets_atendente")
     * @return Response
     */
    public function listarTicketsDoAtendenteAction(): Response
    {
        $ticketsRepo = $this->getDoctrine()->getRepository('AppBundle:Ticket');
        $tickets = $ticketsRepo->findByAtendente($this->getUser());

        return $this->render('tickets/listar.html.twig', ['tickets' => $tickets]);
    }

    /**
     * Exibe os tickets abertos pelo usuário logado ordenados por data
     *
     * @Route("/tickets/usuario", name="meus_tickets")
     * @return Response
     */
    public function listarTicketsAbertosPeloUsuario(): Response
    {
        $ticketsRepo = $this->getDoctrine()->getRepository('AppBundle:Ticket');
        $tickets = $ticketsRepo->findByCriador($this->getUser());
        return $this->render('tickets/listar.html.twig', ['tickets' => $tickets]);
    }

    /**
     * Exibe o formulário de gestão do ticket e altera seus dados com o envio do formulário
     *
     * @Route("/tickets/gerenciar/{id}", name="gerenciar_ticket")
     * @param Ticket $ticket
     * @param Request $request
     * @return Response
     */
    public function gerenciarAction(Ticket $ticket, Request $request): Response
    {
        /** @var Usuario $usuarioLogado */
        $usuarioLogado = $this->getUser();
        if (!$ticket->podeSerGerenciado($usuarioLogado)) {
            $this->addFlash('danger', 'Este ticket não pode ser gerenciado por você no momento.');
            return $this->voltar($request);
        }

        $form = $this->createForm(GerenciarTicketType::class, $ticket);
        try {
            if (!$this->isGranted('ROLE_SUPERVISOR')) {
                $form->remove('atendenteResponsavel');
            }
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $ticket = $form->getData();
                $erros = $this->get('validator')->validate($ticket);

                if (count($erros) === 0) {
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($ticket);
                    $manager->flush();

                    $this->addFlash('success', 'Ticket alterado com sucesso');
                    return $this->redirect($request->getUri());
                }

                $this->addErrosAoEscopoFlash($erros);
            }
        } catch (\InvalidArgumentException $e) {
            // Caso haja erros de validação nas entidades
            $this->addErrosAoEscopoFlash([$e]);
        }

        return $this->render('tickets/gerenciar.html.twig', ['form' => $form->createView(), 'ticket' => $ticket]);
    }

    /**
     * Exibe todos os dados do ticket passado na rota
     *
     * @Route("/tickets/ver/{id}", name="visualizar_ticket")
     * @param Ticket $ticket
     * @return Response
     */
    public function visualizarTicketAction(Ticket $ticket, Request $request): Response
    {
        try {
            if (!$this->getUser()->podeVer($ticket)) {
                throw $this->createAccessDeniedException('Você não tem permissão para visualizar este ticket.');
            }

            return $this->render('tickets/ver.html.twig', compact('ticket'));
        } catch (AccessDeniedException $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->voltar($request);
        }
    }

    /**
     * Fecha o ticket passado por parâmetro
     *
     * @Route("/tickets/fechar/{id}", name="fechar_ticket")
     * @param Ticket $ticket
     * @param Request $request
     * @return Response
     */
    public function fecharTicketAction(Ticket $ticket, Request $request): Response
    {
        $manager = new TicketManager();
        $manager
            ->addAcaoAoFechar($this->get('app.ticket_repository'))
            ->addAcaoAoFechar($this->get('app.email_fechar_ticket'));
        $manager->fechar($ticket);

        $this->addFlash('success', "Ticket #{$ticket->getId()} fechado com sucesso");

        if ($ticket->estaParaAprovacao()) {
            return $this->redirectToRoute('listar_tickets');
        }
        return $this->voltar($request);
    }

    /**
     * Reabre o ticket passado por parâmetro
     *
     * @Route("/tickets/reabrir/{id}", name="reabrir_ticket")
     * @param Ticket $ticket
     * @param Request $request
     * @return Response
     */
    public function reabrirTicketAction(Ticket $ticket, Request $request): Response
    {
        $manager = new TicketManager();
        $manager
            ->addAcaoAoReabrir($this->get('app.ticket_repository'));
        $manager->reabrir($ticket);
        $this->addFlash('success', 'Seu ticket foi reaberto');

        return $this->voltar($request);
    }

    /**
     * Quando o usuário tentar reabrir um ticket já fechado, o mesmo deve ser clonado
     *
     * @Route("/tickets/clonar/{id}", name="clonar_ticket")
     * @param Ticket $ticket
     * @param Request $request
     * @return Response
     */
    public function clonarTicketAction(Ticket $ticket, Request $request): Response
    {
        $novoTicket = clone $ticket;
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($novoTicket);
        $manager->flush();
        $this->addFlash('success', 'Um novo ticket foi criado com todas as informações do ticket selecionado.');

        return $this->voltar($request);
    }

    /**
     * Adiciona os erros passados por parâmetro no escopo flash da sessão
     *
     * @param iterable $erros
     */
    private function addErrosAoEscopoFlash(iterable $erros): void
    {
        foreach ($erros as $erro) {
            $this->addFlash('danger', $erro->getMessage());
        }
    }

    /**
     * Baseado em uma requisição, tenta voltar para a rota anterior. Caso não exista,
     * volta para a listagem de tickets.
     *
     * @param Request $request
     * @return Response
     */
    private function voltar(Request $request): Response
    {
        $anterior = $request->headers->get('referer');
        if (empty($anterior)) {
            return $this->redirectToRoute('listar_tickets');
        }
        return $this->redirect($anterior);
    }

    /**
     * Envia a mensagem contida no corpo do post para o ticket
     *
     * @Route("/tickets/{id}/enviar-mensagem", name="enviar_mensagem")
     * @Method("POST")
     * @param Ticket $ticket
     * @param Request $request
     * @return Response
     */
    public function enviarMensagemAction(Ticket $ticket, Request $request): Response
    {
        $textoMensagem = $request->request->get('mensagem');
        $manager = new TicketManager();
        $manager
            ->addAcaoAoInteragir($this->get('app.ticket_repository'))
            ->addAcaoAoInteragir($this->get('app.email_interacao_ticket'));
        $manager->interagir($ticket, $textoMensagem, $this->getUser());

        return $this->voltar($request);
    }
}
