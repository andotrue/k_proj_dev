<?php

namespace Machigai\GameBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelpControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/help/');
    }

    public function testHowtoplay()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/help/howtoplay');
    }

    public function testTerms()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/help/terms');
    }

    public function testInquiry()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/help/inquiry');
    }

}
