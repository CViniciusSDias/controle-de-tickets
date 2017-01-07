<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\{
    ChoiceType, EmailType, PasswordType, SubmitType, TextType
};
use Symfony\Component\HttpFoundation\{Request, Response};

class UsuariosController extends Controller
{
    /**
     * @Route("/usuarios", name="listar_usuarios")
     */
    public function listarAction(): Response
    {
        $usuarios = $this->getDoctrine()->getRepository('AppBundle:Usuario')->findAll();

        return $this->render('usuarios/listar.html.twig', ['usuarios' => $usuarios]);
    }

    /**
     * @Route("/usuarios/novo", name="cadastrar_usuario")
     * @param Request $request
     * @return Response
     */
    public function cadastrarAction(Request $request): Response
    {
        $usuario = new Usuario();
        $form = $this->createFormBuilder($usuario)
            ->add('nome', TextType::class)
            ->add('email', EmailType::class)
            ->add('senha', PasswordType::class)
            ->add('salvar', SubmitType::class)
            ->add('tipo', ChoiceType::class, [
                'choices' => [
                    'Tipo' => '',
                    'Usuário' => 'ROLE_USER',
                    'Suporte' => 'ROLE_ADMIN',
                    'Administrador' => 'ROLE_SUPER_ADMIN'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            echo 'Teste';
        }

        return $this->render('usuarios/cadastrar.html.twig', ['form' => $form->createView()]);
    }
}
