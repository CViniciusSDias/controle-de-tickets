<?php
namespace AppBundle\Controller;


use AppBundle\Entity\Ticket;
use AppBundle\Forms\{CriarTicketType, GerenciarTicketType};
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\{Request, Response};

class TicketsController extends Controller
{
    /**
     * Raiz do projeto
     *
     * @Route("/")
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('cadastrar_ticket');
    }

    /**
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

                if (count($erros) > 0) {
                    $this->adicionaErrosAoEscopoFlash($erros);
                } else {
                    // Se o ticket passar na validação, salva no BD e recarrega a página
                    $em = $this->getDoctrine()->getManager();
                    $ticket->usuarioCriador = $this->getUser();
                    $em->persist($ticket);
                    $em->flush();

                    $this->addFlash('success', 'Ticket cadastrado com sucesso');

                    return $this->redirect($request->getUri());
                }
            }
        } catch (\InvalidArgumentException $e) {
            $this->adicionaErrosAoEscopoFlash(array($e));
        }

        return $this->render('tickets/cadastrar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tickets/{id}/assumir", name="assumir_responsabilidade")
     * @return Response
     */
    public function assumirResponsabilidadeAction(Ticket $ticket, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $ticket->setAtendenteResponsavel($this->getUser());
        $em->persist($ticket);
        $em->flush();

        $this->addFlash('success', 'O tícket está agora sob sua responsabilidade.');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
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
     * @Route("/tickets/abertos", name="listar_tickets_abertos")
     * @return Response
     */
    public function listarAbertosAction(): Response
    {
        $tickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')
            ->findBy(['aberto' => true]);
        return $this->render('tickets/listar.html.twig', ['tickets' => $tickets]);
    }

    /**
     * @Route("/tickets/fechados", name="listar_tickets_fechados")
     * @return Response
     */
    public function listarFechadosAction(): Response
    {
        $tickets = $this->getDoctrine()->getRepository('AppBundle:Ticket')
            ->findBy(['aberto' => false]);
        return $this->render('tickets/listar.html.twig', ['tickets' => $tickets]);
    }

    /**
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
     * @Route("/tickets/{id}", name="gerenciar_ticket")
     * @return Response
     */
    public function gerenciarAction(Request $request, int $id): Response
    {
        try {
            $ticket = $this->getDoctrine()->getRepository('AppBundle:Ticket')->find($id);
            $form = $this->createForm(GerenciarTicketType::class, $ticket);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $ticket = $form->getData();
                $validador = $this->get('validator');
                $erros = $validador->validate($ticket);

                if (count($erros) === 0) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($ticket);
                    $em->flush();

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

    private function adicionaErrosAoEscopoFlash(array $erros)
    {
        foreach ($erros as $erro) {
            $this->addFlash('danger', $erro->getMessage());
        }
    }
}