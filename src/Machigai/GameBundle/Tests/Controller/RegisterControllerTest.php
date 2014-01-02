<?php

namespace Machigai\GameBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
    }

    public function testComplete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register/complete');
    }

}
