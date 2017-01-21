<?php
/**
 * Created by PhpStorm.
 * User: vinicius
 * Date: 30/12/16
 * Time: 23:23
 */

namespace AppBundle\Controller;

use AppBundle\Entity\MensagemRecuperacaoSenha;
use AppBundle\Entity\TokenSenha;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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

            $link = $request->getHost() . '/recuperar-senha/' . $token->getToken();
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
        $this->addFlash('success', 'Um e-mail foi enviado para o endereço informado, contendo um link para redefinição de senha');
        return $this->redirect('login');
    }
}
