<?php
/**
 * Created by PhpStorm.
 * User: vinicius
 * Date: 04/01/17
 * Time: 22:29
 */

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $this->login($client);
    }

    public function login(Client $client)
    {
        $crawler = $client->request('GET', '/login');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Faça login para começar")')->count());

        $form = $crawler->filter('button[type="submit"]')->form();
        $form['_username'] = 'carlosv775@gmail.com';
        $form['_password'] = 'admin';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
    }
}