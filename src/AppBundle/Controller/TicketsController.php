<?php
namespace AppBundle\Controller;


use AppBundle\Entity\Ticket;
use AppBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketsController extends Controller
{
    /**
     * @Route("/tickets/novo", name="cadastrar_ticket")
     * @Method("GET")
     */
    public function cadastrarAction(): Response
    {
        return $this->render('tickets/cadastrar.html.twig');
    }

    /**
     * @Route("/tickets/novo", name="inserir_ticket")
     * @Method("POST")
     */
    public function inserirAction(Request $request): Response
    {
        $titulo = $request->request->get('titulo-ticket');
        $descricao = $request->request->get('descricao-ticket');
        $doctrine = $this->getDoctrine();

        $usuario = $doctrine->getRepository('AppBundle:Usuario')->find(1);

        $ticket = new Ticket();
        $ticket->titulo = $titulo;
        $ticket->descricao = $descricao;
        $ticket->usuarioCriador = $usuario;

        $em = $doctrine->getManager();
        $em->persist($ticket);
        $em->flush();

        $this->addFlash('success', 'Ticket cadastrado com sucesso');

        return $this->redirectToRoute('cadastrar_ticket');
    }
}