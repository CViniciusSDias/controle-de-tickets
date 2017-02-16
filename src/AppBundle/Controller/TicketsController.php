<?php
namespace AppBundle\Controller;

use AppBundle\Entity\EstadoTicket;
use AppBundle\Entity\Ticket;
use AppBundle\Forms\{CriarTicketType, GerenciarTicketType};
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\{Request, Response};
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
     * Raiz do projeto
     *
     * @Route("/")
     * @return Response Redireciona o usuário para a tela de abertura de um novo ticket
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('cadastrar_ticket');
    }

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
                $ticket = $form->getData();
                $erros = $this->get('validator')->validate($ticket);

                if (count($erros) === 0) {
                    // Se o ticket passar na validação, salva no BD e recarrega a página
                    $manager = $this->getDoctrine()->getManager();
                    $ticket->usuarioCriador = $this->getUser();
                    $manager->persist($ticket);
                    $manager->flush();

                    $this->addFlash('success', 'Ticket cadastrado com sucesso');

                    return $this->redirect($request->getUri());
                }

                $this->adicionaErrosAoEscopoFlash($erros);
            }
        } catch (\InvalidArgumentException $e) {
            $this->adicionaErrosAoEscopoFlash(array($e));
        }

        return $this->render('tickets/cadastrar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Faz com que o usuário logado passe a ser o responsável pelo ticket passado na rota
     *
     * @Route("/tickets/{id}/assumir", name="assumir_responsabilidade")
     * @param Ticket $ticket
     * @param Request $request
     * @return Response

    public function assumirResponsabilidadeAction(Ticket $ticket, Request $request): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $ticket->setAtendenteResponsavel($this->getUser());
        $manager->persist($ticket);
        $manager->flush();

        $this->addFlash('success', 'O tícket está agora sob sua responsabilidade.');
        return $this->voltar($request);
    }*/

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
        $tickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')
            ->findBy(['estado' => new EstadoTicket(EstadoTicket::ABERTO)]);
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
        $tickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')
            ->findBy(['estado' => new EstadoTicket(EstadoTicket::FECHADO)]);
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
        $tickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')
            ->findBy(['atendenteResponsavel' => $this->getUser()]);
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
        $tickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')
            ->findBy(['usuarioCriador' => $this->getUser()]);
        return $this->render('tickets/listar.html.twig', ['tickets' => $tickets]);
    }

    /**
     * Exibe o formulário de gestão do ticket e altera seus dados com o envio do formulário
     *
     * @Route("/tickets/{id}", name="gerenciar_ticket")
     * @param Ticket $ticket
     * @param Request $request
     * @return Response
     */
    public function gerenciarAction(Ticket $ticket, Request $request): Response
    {
        try {
            $form = $this->createForm(GerenciarTicketType::class, $ticket);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $ticket = $form->getData();
                $validador = $this->get('validator');
                $erros = $validador->validate($ticket);

                if (count($erros) === 0) {
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($ticket);
                    $manager->flush();

                    $this->addFlash('success', 'Ticket alterado com sucesso');
                    return $this->redirect($request->getUri());
                }

                $this->adicionaErrosAoEscopoFlash($erros);
            }
        } catch (\InvalidArgumentException $e) {
            // Caso haja erros de validação nas entidades
            $this->adicionaErrosAoEscopoFlash([$e]);
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
     * @return Response
     */
    public function fecharTicketAction(Ticket $ticket, Request $request): Response
    {
        $ticket->fechar();
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($ticket);
        $manager->flush();

        return $this->voltar($request);
    }

    /**
     * Adiciona os erros passados por parâmetro no escopo flash da sessão
     *
     * @param array $erros
     */
    private function adicionaErrosAoEscopoFlash(array $erros): void
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
}
