<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Usuario;
use AppBundle\Forms\CriarUsuarioType;
use AppBundle\Forms\EditarTipoUsuarioType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\{Request, Response};

/**
 * Controller para Usuários
 *
 * @author Vinicius Dias
 * @package AppBundle\Controller
 */
class UsuariosController extends Controller
{
    /**
     * Exibe a listagem de todos os usuários
     *
     * @Route("/usuarios", name="listar_usuarios")
     * @return Response
     */
    public function listarAction(): Response
    {
        $usuarios = $this->getDoctrine()->getRepository('AppBundle:Usuario')->findAll();

        return $this->render('usuarios/listar.html.twig', ['usuarios' => $usuarios]);
    }

    /**
     * Exibe o formulário de cadastro de usuário e cria um novo usuário com seus dados
     *
     * @Route("/usuarios/novo", name="cadastrar_usuario")
     * @param Request $request
     * @return Response
     */
    public function cadastrarAction(Request $request): Response
    {
        try {
            $usuario = new Usuario();
            $form = $this->createForm(CriarUsuarioType::class, $usuario);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $usuario = $form->getData();
                $encoder = $this->container->get('security.password_encoder');
                $usuario->setSenha($encoder->encodePassword($usuario, $usuario->getSenha()));
                $validador = $this->get('validator');
                $erros = $validador->validate($usuario);
                if (count($erros) > 0) {
                    throw new \InvalidArgumentException($erros[0]->getMessage());
                }
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($usuario);
                $manager->flush();
                $this->addFlash('success', "Usuário {$usuario->getNome()} adicionado com sucesso");

                return $this->redirect($request->getUri());
            }
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->render('usuarios/cadastrar.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Remove o usuário passado por parâmetro (POST)
     *
     * @param Request $request Requisição http necessariamente com o parâmetro 'id'
     * @return Response
     * @Route("/usuarios/remover", name="remover_usuario")
     */
    public function removerAction(Request $request): Response
    {
        $idUsuario = $request->request->get('id');
        $manager = $this->getDoctrine()->getManager();
        $categoria = $manager->getPartialReference(Usuario::class, ['id' => $idUsuario]);
        try {
            $manager->remove($categoria);
            $manager->flush();

            $this->addFlash('success', 'Usuário removido com sucesso');
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash(
                'danger',
                'Impossível remover. Este usuário cadastrou tickets ou tem tickets sob sua responsabilidade.'
            );
        }

        return $this->redirectToRoute('listar_usuarios');
    }

    /**
     * Exibe o form de edição do tipo do usuário, e o altera com o dado enviado na requisição
     *
     * @Route("/usuarios/editar/{id}", name="editar_usuario")
     * @param Usuario $usuario
     * @param Request $request
     * @return Response
     */
    public function editarAction(Usuario $usuario, Request $request): Response
    {
        $form = $this->createForm(EditarTipoUsuarioType::class, $usuario);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Atualiza o usuário com seu novo tipo
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Tipo do usúario alterado com sucesso');
            return $this->redirectToRoute('listar_usuarios');
        }
        return $this->render('usuarios/editar-tipo.html.twig', ['usuario' => $usuario, 'form' => $form->createView()]);
    }
}
