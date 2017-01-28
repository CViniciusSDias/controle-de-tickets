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
     * @param string $firewallName
     * @param array $options
     * @param array $server
     * @return Client
     */
    protected function createClientWithAuthentication($firewallName, array $options = array(), array $server = array())
    {
        /* @var $client Client */
        $client = static::createClient($options, $server);

        /* @var $user UserInterface */
        $user = $client->getContainer()->get('doctrine')->getRepository('AppBundle:Usuario')->find(5);

        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewallName, $user->getRoles());
        $session = $client->getContainer()->get('session');
        $session->set('_security_' . $firewallName, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

        return $client;
    }

}