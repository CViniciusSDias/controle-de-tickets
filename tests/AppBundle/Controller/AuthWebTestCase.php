<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AuthWebTestCase extends WebTestCase
{
    /**
     * @return Client
     */
    protected function createClientWithAuthentication()
    {
        /* @var $client Client */
        $client = static::createClient();

        /* @var $user UserInterface */
        $user = $client
            ->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:Usuario')
            ->find(1);

        $firewallName = 'main';
        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewallName, $user->getRoles());
        $session = $client->getContainer()->get('session');
        $session->set('_security_' . $firewallName, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

        return $client;
    }

}