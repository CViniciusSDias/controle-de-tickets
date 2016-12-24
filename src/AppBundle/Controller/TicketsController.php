<?php
namespace AppBundle\Controller;


use AppBundle\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\{SubmitType, TextareaType, TextType};
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\{Request, Response};

class TicketsController extends Controller
{
    /**
     * Raiz do projeto
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
        $ticket = new Ticket();
        $form = $this->criarForm($ticket);
        $form->handleRequest($request);

        /** Caso seja uma requisição post, e o formulário já tenha sido enviado */
        if ($form->isSubmitted()) {
            $ticket = $form->getData();
            $validador = $this->get('validator');
            $erros = $validador->validate($ticket);

            /** Se o ticket passar na validação, salva no BD e recarrega a página */
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

    /**
     * Factory Method do formulário para inserção de ticket
     * @param Ticket $ticket
     * @return FormInterface
     */
    private function criarForm(Ticket $ticket): FormInterface
    {
        return $this->createFormBuilder($ticket)
            ->add('titulo', TextType::class)
            ->add('descricao', TextareaType::class, ['required' => false])
            ->add('categoria', EntityType::class, [
                'class' => 'AppBundle:Categoria',
                'choice_label' => 'nome',
                'placeholder' => 'Selecione'
            ])->add('salvar', SubmitType::class, ['label' => 'Salvar'])
            ->getForm();
    }
}