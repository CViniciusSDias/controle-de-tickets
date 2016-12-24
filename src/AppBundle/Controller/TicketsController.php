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
        $categorias = $this->getDoctrine()->getRepository('AppBundle:Categoria')->findAll();
        return $this->render('tickets/cadastrar.html.twig', ['categorias' => $categorias]);
    }

    /**
     * @Route("/tickets/novo", name="inserir_ticket")
     * @Method("POST")
     */
    public function inserirAction(Request $request): Response
    {
        $titulo = $request->request->get('titulo-ticket');
        $descricao = $request->request->get('descricao-ticket');
        $idCategoria = $request->request->get('categoria-ticket');
        $doctrine = $this->getDoctrine();
        $validador = $this->get('validator');

        $usuario = $doctrine->getRepository('AppBundle:Usuario')->find(1);

        $ticket = new Ticket();
        $ticket->titulo = $titulo;
        $ticket->descricao = $descricao;
        $ticket->usuarioCriador = $usuario;
        $ticket->categoria = $doctrine->getRepository('AppBundle:Categoria')->find($idCategoria);

        $erros = $validador->validate($ticket);

        if (count($erros) > 0) {
            foreach ($erros as $erro) {
                $this->addFlash('danger', $erro->getMessage());
            }
        } else {
            $em = $doctrine->getManager();
            $em->persist($ticket);
            $em->flush();

            $this->addFlash('success', 'Ticket cadastrado com sucesso');
        }

        return $this->redirectToRoute('cadastrar_ticket');
    }
}