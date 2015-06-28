<?php

namespace Kanahei\GameBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testSelect()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/game/select');
    }

    public function testPlay()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/game/play');
    }

    public function testFinish()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/game/finish');
    }

    public function testClear()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/game/clear');
    }

    public function testFail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/game/fail');
    }

}
