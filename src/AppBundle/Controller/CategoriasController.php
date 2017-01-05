<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categoria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\{Request, Response};

class CategoriasController extends Controller
{
    /**
     * Ação de listagem de categorias, e formulário para adição de uma nova.
     * Caso a requisição seja post, salva a categoria no banco de dados
     * @param Request $request
     * @return Response
     * @Route("/categorias", name="listar_categorias")
     */
    public function listarAction(Request $request): Response
    {
        $categorias = $this->getDoctrine()->getRepository('AppBundle:Categoria')
            ->findBy([], ['nome' => 'asc']);

        $categoria = new Categoria();
        $form = $this->createFormBuilder($categoria)
            ->add('nome', TextType::class)
            ->add('salvar', SubmitType::class, ['label' => 'Salvar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $categoria = $form->getData();
            $validador = $this->get('validator');
            $erros = $validador->validate($categoria);

            if (count($erros) === 0) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($categoria);
                $em->flush();
                $this->addFlash('success', 'Categoria adicionada com sucesso');
                return $this->redirect($request->getUri());
            }

            foreach ($erros as $erro) {
                $this->addFlash('danger', $erro->getMessage());
            }
        }

        return $this->render(
            'categorias/listar.html.twig',
            ['categorias' => $categorias, 'form' => $form->createView()]
        );
    }

    /**
     * Ação que remove a categoria passada por parâmetro (POST)
     * @param Request $request Requisição http necessariamente com o parâmetro 'id'
     * @return Response
     * @Route("/categorias/remover", name="remover_categoria")
     */
    public function removerAction(Request $request): Response
    {
        $idCategoria = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $categoria = $em->getPartialReference(Categoria::class, ['id' => $idCategoria]);
        $em->remove($categoria);
        $em->flush();

        $this->addFlash('success', 'Categoria removida com sucesso');

        return $this->redirectToRoute('listar_categorias');
    }
}
