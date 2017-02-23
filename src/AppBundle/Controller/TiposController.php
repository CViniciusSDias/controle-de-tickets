<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Tipo;
use AppBundle\Forms\CriarTipoType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\{Request, Response};

/**
 * Controller para Tipos
 *
 * @author Vinicius Dias
 * @package AppBundle\Controller
 */
class TiposController extends Controller
{

    /**
     * Ação de listagem de tipos.
     *
     * @Route("/tipos", name="listar_tipos")
     * @return Response
     */
    public function listarAction(): Response
    {
        $doctrine = $this->getDoctrine();
        $tipos = $doctrine->getRepository('AppBundle:Tipo')
            ->findBy([], ['nome' => 'asc']);

        return $this->render(
            'tipos/listar.html.twig',
            ['tipos' => $tipos]
        );
    }

    /**
     * Ação para cadastrar um novo Tipo
     *
     * @Route("/tipos/novo", name="cadastrar_tipo")
     * @param Request $request
     * @return Response
     */
    public function cadastrarAction(Request $request): Response
    {
        $doctrine = $this->getDoctrine();
        $form = $this->createForm(CriarTipoType::class, new Tipo());

        try {
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $tipo = $form->getData();
                $validador = $this->get('validator');
                $erros = $validador->validate($tipo);

                if (count($erros) === 0) {
                    $manager = $doctrine->getManager();
                    $manager->persist($tipo);
                    $manager->flush();
                    $this->addFlash('success', 'Tipo adicionado com sucesso');

                    return $this->redirectToRoute('listar_tipos');
                }

                foreach ($erros as $erro) {
                    $this->addFlash('danger', $erro->getMessage());
                }
            }
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->render(
            'tipos/cadastrar.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Ação que remove o tipo passado por parâmetro (POST)
     *
     * @Route("/tipos/remover", name="remover_categoria")
     * @param Request $request Requisição http necessariamente com o parâmetro 'id'
     * @return Response
     */
    public function removerAction(Request $request): Response
    {
        $idCategoria = $request->request->get('id');
        $manager = $this->getDoctrine()->getManager();
        $categoria = $manager->getPartialReference(Tipo::class, ['id' => $idCategoria]);
        try {
            $manager->remove($categoria);
            $manager->flush();

            $this->addFlash('success', 'Tipo removido com sucesso');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash('danger', 'O tipo selecionado possui tickets relacionados a ele.');
        }

        return $this->redirectToRoute('listar_tipos');
    }

    /**
     * Ação para editar dados de um tipo
     *
     * @Route("/tipos/editar/{id}", name="editar_tipo")
     * @param Tipo $tipo
     * @param Request $request
     * @return Response
     */
    public function editaAction(Tipo $tipo, Request $request): Response
    {
        $form = $this->createForm(CriarTipoType::class, $tipo);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $tipo = $form->getData();
            $validator = $this->get('validator');
            $erros = $validator->validate($tipo);

            if (count($erros) === 0) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($tipo);
                $em->flush();
                $this->addFlash('success', 'Tipo editado com sucesso');
                return $this->redirectToRoute('listar_tipos');
            }

            foreach ($erros as $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('tipos/cadastrar.html.twig', ['form' => $form->createView()]);
    }
}
