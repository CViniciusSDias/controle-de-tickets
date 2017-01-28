<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Categoria;
use AppBundle\Forms\CriarCategoriaType;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\{Request, Response};

/**
 * Controller para Categorias
 *
 * @author Vinicius Dias
 * @package AppBundle\Controller
 */
class CategoriasController extends Controller
{
    /**
     * Ação de listagem de categorias, e formulário para adição de uma nova.
     * Caso a requisição seja post, salva a categoria no banco de dados
     *
     * @Route("/categorias", name="listar_categorias")
     * @param Request $request
     * @return Response
     */
    public function listarAction(Request $request): Response
    {
        $doctrine = $this->getDoctrine();
        $categorias = $doctrine->getRepository('AppBundle:Categoria')
            ->findBy([], ['nome' => 'asc']);
        $form = $this->createForm(CriarCategoriaType::class, new Categoria());

        try {
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $categoria = $form->getData();
                $validador = $this->get('validator');
                $erros = $validador->validate($categoria);

                if (count($erros) === 0) {
                    $manager = $doctrine->getManager();
                    $manager->persist($categoria);
                    $manager->flush();
                    $this->addFlash('success', 'Categoria adicionada com sucesso');
                    return $this->redirect($request->getUri());
                }

                foreach ($erros as $erro) {
                    $this->addFlash('danger', $erro->getMessage());
                }
            }
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->render(
            'categorias/listar.html.twig',
            ['categorias' => $categorias, 'form' => $form->createView()]
        );
    }

    /**
     * Ação que remove a categoria passada por parâmetro (POST)
     *
     * @Route("/categorias/remover", name="remover_categoria")
     * @param Request $request Requisição http necessariamente com o parâmetro 'id'
     * @return Response
     */
    public function removerAction(Request $request): Response
    {
        $idCategoria = $request->request->get('id');
        $manager = $this->getDoctrine()->getManager();
        $categoria = $manager->getPartialReference(Categoria::class, ['id' => $idCategoria]);
        try {
            $manager->remove($categoria);
            $manager->flush();

            $this->addFlash('success', 'Categoria removida com sucesso');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('danger', 'A categoria selecionada possui tickets relacionados a ela.');
        }

        return $this->redirectToRoute('listar_categorias');
    }
}
