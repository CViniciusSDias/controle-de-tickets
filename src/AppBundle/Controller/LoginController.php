<?php
namespace AppBundle\Controller;

use AppBundle\Entity\{
    MensagemRecuperacaoSenha, TokenSenha, Usuario
};
use AppBundle\Forms\RedefinirSenhaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Exception;
use TypeError;

/**
 * Controller para rotas referentes ao login, como o login em si, formulário de redefinição de senha, etc.
 *
 * @author Vinicius Dias
 * @package AppBundle\Controller
 */
class LoginController extends Controller
{
    /**
     * Exibe o fomrulário de login e trata seu envio para autenticar o usuário
     *
     * @Route("/login", name="login")
     * @return Response
     */
    public function loginAction(): Response
    {
        $auth = $this->get('security.authentication_utils');
        $erro = $auth->getLastAuthenticationError();

        return $this->render('seguranca/login.html.twig', compact('erro'));
    }

    /**
     * Exibe o formulário para o usuário digitar seu e-mail informando que esqueceu sua senha
     *
     * @Route("/esqueci-a-senha", name="esqueci-a-senha")
     * @return Response
     */
    public function esqueciASenhaAction(): Response
    {
        return $this->render('seguranca/esqueci-a-senha.html.twig');
    }

    /**
     * Envia um e-mail com o link para redefinição de senha do usuário
     *
     * @Route("/enviar-recuperacao", name="enviar-recuperacao")
     * @Method("POST")
     * @param Request $request Requisição contendo o e-mail do usuário
     * @return Response
     */
    public function enviarRecuperacaoAction(Request $request): Response
    {
        $email = $request->request->get('email');

        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('E-mail inválido');
            }

            $doctrine = $this->getDoctrine();
            $token = $this->get('app.token_generator')->generateToken($email);
            $manager = $doctrine->getManager();
            $manager->persist($token);
            $manager->flush();

            $this->get('app.email_recuperacao_senha')->sendMail($token, $email);
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirect($request->headers->get('referer'));
        } catch (TypeError $e) {
            $this->get('logger')->warning('Tentativa de recuperação de senha com e-mail: ' . $email);
        }

        /*
         Caso o usuário digite um e-mail errado, não se deve informar que o e-mail
         não existe, pois abre uma brecha de segurança.
        */
        $this->addFlash(
            'success',
            'Um e-mail foi enviado para o endereço informado, contendo um link para redefinição de senha'
        );
        return $this->redirectToRoute('login');
    }

    /**
     * Exibe o formulário para redefinir a senha após verificar a validade do ticket e invalidá-lo
     *
     * @Route("/recuperar-senha/{token}", name="recuperar_senha")
     * @param string $token Token gerado para redefinição de senha
     * @param Request $request
     * @return Response
     */
    public function redefinirSenhaAction(string $token, Request $request): Response
    {
        try {
            $doctrine = $this->getDoctrine();
            $manager = $doctrine->getManager();
            $token = $doctrine->getRepository('AppBundle:TokenSenha')->findOneBy(['token' => $token]);

            if (is_null($token)) {
                throw new \RuntimeException('Link inválido');
            }

            if (!$token->isAtivo()) {
                throw new \RuntimeException('Este link expirou.');
            }

            $form = $this->createForm(RedefinirSenhaType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $validator = $this->get('validator');
                $erros = $validator->validate($form);

                if (count($erros) === 0) {
                    $encoder = $this->container->get('security.password_encoder');
                    /** @var Usuario $usuario */
                    $usuario = $token->getUsuario();
                    $novaSenha = $encoder->encodePassword($usuario, $form->get('novaSenha')->getData());
                    $usuario->setSenha($novaSenha);
                    $token->desativar();

                    $manager->flush();

                    $this->addFlash('success', 'Sua senha foi redefinida com sucesso');
                    return $this->redirectToRoute('login');
                }
            }

            return $this->render('seguranca/redefinir-senha.html.twig', ['form' => $form->createView()]);
        } catch (\RuntimeException $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirectToRoute('login');
        }
    }
}
