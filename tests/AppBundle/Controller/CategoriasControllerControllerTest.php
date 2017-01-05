<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoriasControllerControllerTest extends WebTestCase
{
    public function testListar()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/categorias');
    }

}
