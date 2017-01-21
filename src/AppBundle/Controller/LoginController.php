<?php
/**
 * Created by PhpStorm.
 * User: vinicius
 * Date: 30/12/16
 * Time: 23:23
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $auth = $this->get('security.authentication_utils');
        $erro = $auth->getLastAuthenticationError();

        return $this->render('login.html.twig', compact('erro'));
    }
}
