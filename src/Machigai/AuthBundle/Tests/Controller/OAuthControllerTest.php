<?php

namespace Machigai\AuthBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OAuthControllerTest extends WebTestCase
{
    public function testResponsetoken()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/auth/oauth/response_token');
    }

}
