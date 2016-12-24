<?php
namespace AppBundle\Controller;


use AppBundle\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketsController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('cadastrar_ticket');
    }

    /**
     * @Route("/tickets/novo", name="cadastrar_ticket")
     */
    public function cadastrarAction(Request $request): Response
    {
        $ticket = new Ticket();
        $form = $this->createFormBuilder($ticket)
            ->add('titulo', TextType::class)
            ->add('descricao', TextareaType::class, ['required' => false])
            ->add('categoria', EntityType::class, [
                'class' => 'AppBundle:Categoria',
                'choice_label' => 'nome',
                'placeholder' => 'Selecione'
            ])->add('salvar', SubmitType::class, ['label' => 'Salvar'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $ticket = $form->getData();
            $validador = $this->get('validator');
            $erros = $validador->validate($ticket);

            if (count($erros) === 0) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ticket);
                $em->flush();

                $this->addFlash('success', 'Ticket cadastrado com sucesso');

                return $this->redirect($request->getUri());
            } else {
                foreach ($erros as $erro) {
                    $this->addFlash('danger', $erro->getMessage());
                }
            }
        }

        return $this->render('tickets/cadastrar.html.twig', [
            'form' => $form->createView()
        ]);
    }
}