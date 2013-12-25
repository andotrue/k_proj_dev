<?php

namespace Machigai\GameBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SettingControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/setting/');
    }

    public function testUsername()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/setting/username');
    }

    public function testComplete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/setting/complete');
    }

}
