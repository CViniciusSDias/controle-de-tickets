<?php

namespace AppBundle\Controller;

use AppBundle\Entity\{RedefinicaoDeSenha,Usuario};
use AppBundle\Forms\{EditarDadosPerfilType, EditarSenhaPerfilType};
use Sensio\Bundle\FrameworkExtraBundle\Configuration\{Route, Method};
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PerfilController extends Controller
{
    /**
     * @Route("/perfil", name="perfil")
     * @Method("GET")
     */
    public function perfilAction()
    {
        $usuario = $this->getUser();
        $ticketDao = $this->getDoctrine()->getRepository('AppBundle:Ticket');
        $numTicketsAbertos = $ticketDao->ticketsAbertosPor($usuario);
        $numTicketsResponsavel = $ticketDao->ticketsSobResponsabilidade($usuario);
        $formDados = $this->createForm(EditarDadosPerfilType::class, $usuario)->createView();
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
     * @Route("/alterar-senha", name="alterar_senha")
     * @Method("POST")
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
}
