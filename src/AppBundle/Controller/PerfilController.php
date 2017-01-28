<?php

namespace AppBundle\Controller;

use AppBundle\Entity\{RedefinicaoDeSenha,Usuario};
use AppBundle\Forms\{EditarDadosPerfilType, EditarSenhaPerfilType};
use Sensio\Bundle\FrameworkExtraBundle\Configuration\{Route, Method};
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller de Perfil do usuário
 *
 * @author Vinicius Dias
 * @package AppBundle\Controller
 */
class PerfilController extends Controller
{
    /**
     * Exibe a tela com informaçẽos do perfil do usuário
     *
     * @Route("/perfil", name="perfil")
     * @Method("GET")
     * @return Response
     */
    public function perfilAction(): Response
    {
        $usuario = $this->getUser();
        $ticketDao = $this->getDoctrine()->getRepository('AppBundle:Ticket');
        $numTicketsAbertos = $ticketDao->ticketsAbertosPor($usuario);
        $numTicketsResponsavel = $ticketDao->ticketsSobResponsabilidade($usuario);
        $formDados = $this->createForm(
            EditarDadosPerfilType::class,
            $usuario,
            ['action' => $this->generateUrl('alterar_dados_perfil')]
        )->createView();
        $formSenha = $this->createForm(
            EditarSenhaPerfilType::class,
            new RedefinicaoDeSenha(),
            ['action' => $this->generateUrl('alterar_senha')]
        )->createView();

        return $this->render(
            'usuarios/perfil.html.twig',
            compact('numTicketsAbertos', 'numTicketsResponsavel', 'formDados', 'formSenha')
        );
    }

    /**
     * Tenta alterar a senha do usuário, caso todos os dados estejam corretos
     *
     * @Route("/alterar-senha", name="alterar_senha")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function alterarSenha(Request $request): Response
    {
        try {
            $form = $this->createForm(EditarSenhaPerfilType::class, new RedefinicaoDeSenha());
            $form->handleRequest($request);

            if (!$form->isSubmitted()) {
                throw new \Exception();
            }

            $erros = $this->get('validator')->validate($form);

            if (count($erros) > 0) {
                throw new \Exception();
            }
            /** @var RedefinicaoDeSenha $redefinicao */
            $redefinicao = $form->getData();
            $encoder = $this->get('security.password_encoder');
            /** @var Usuario $usuarioLogado */
            $usuarioLogado = $this->getUser();
            if (!$encoder->isPasswordValid($usuarioLogado, $redefinicao->getSenhaAtual())) {
                throw new \Exception('Digite corretamente a senha atual');
            }

            $novaSenha = $encoder->encodePassword($usuarioLogado, $redefinicao->getNovaSenha());
            $usuarioLogado->setSenha($novaSenha);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Senha alterada com sucesso');
        } catch (\Exception $e) {
            if (!empty($e->getMessage())) {
                $this->addFlash('danger', $e->getMessage());
            }
        } finally {
            return $this->redirectToRoute('perfil');
        }
    }

    /**
     * Altera os dados do usuário como nome e e-mail
     *
     * @Route("/perfil/alterar", name="alterar_dados_perfil")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function alterarDados(Request $request): Response
    {
        try {
            $usuario = $this->getUser();
            $form = $this->createForm(EditarDadosPerfilType::class, $usuario);
            $form->handleRequest($request);

            if (!$form->isSubmitted()) {
                throw new \Exception();
            }

            $erros = $this->get('validator')->validate($form);

            if (count($erros) > 0) {
                foreach ($erros as $e) {
                    $this->addFlash('danger', $e->getMessage());
                }
                throw new \Exception();
            }
            $usuario = $form->getData();
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Informações alteradas com sucesso');
        } catch (\Exception $e) {
            if (!empty($e->getMessage())) {
                $this->addFlash('danger', $e->getMessage());
            }
        } finally {
            return $this->redirectToRoute('perfil');
        }
    }
}
