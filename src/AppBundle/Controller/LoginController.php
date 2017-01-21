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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use DateTime;
use DateInterval;
use Exception;
use TypeError;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $auth = $this->get('security.authentication_utils');
        $erro = $auth->getLastAuthenticationError();

        return $this->render('seguranca/login.html.twig', compact('erro'));
    }

    /**
     * @Route("/esqueci-a-senha", name="esqueci-a-senha")
     */
    public function esqueciASenhaAction()
    {
        return $this->render('seguranca/esqueci-a-senha.html.twig');
    }

    /**
     * @Route("/enviar-recuperacao", name="enviar-recuperacao")
     * @Method("POST")
     */
    public function enviarRecuperacaoAction(Request $request)
    {
        $email = $request->request->get('email');

        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('E-mail inválido');
            }

            $doctrine = $this->getDoctrine();

            $token = new TokenSenha();
            $token->setToken(sha1(time()))->setExpiracao((new DateTime())->add(new DateInterval('P1D')))
                ->setUsuario($doctrine->getRepository('AppBundle:Usuario')->findOneBy(['email' => $email]));

            $manager = $doctrine->getManager();
            $manager->persist($token);
            $manager->flush();

            $link = $this->generateUrl(
                'recuperar_senha',
                ['token' => $token->getToken()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            $mensagem = MensagemRecuperacaoSenha::newInstance()
                ->setFrom('recuperacao@zer0.w.pw')
                ->setTo($email)
                ->setBody($this->renderView('seguranca/email-recuperacao.html.twig', ['link' => $link]));

            $this->get('mailer')->send($mensagem);
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirect($request->headers->get('referer'));
        } catch (TypeError $e) {
            $this->get('logger')->warning('Tentativa de recuperação de senha com e-mail: ' . $email);
        }

        /*
         Caso o usuário digite um e-mail errado, não se deve informar que o e-mail não existe, pois abre uma brecha
         de segurança.
        */
        $this->addFlash(
            'success',
            'Um e-mail foi enviado para o endereço informado, contendo um link para redefinição de senha'
        );
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/recuperar-senha/{token}", name="recuperar_senha")
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
