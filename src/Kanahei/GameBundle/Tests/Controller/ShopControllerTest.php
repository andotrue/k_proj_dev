<?php

namespace Kanahei\GameBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShopControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/shop/');
    }

    public function testWallpaper()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/shop/wallpaper/');
    }

    public function testStamp()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/shop/stamp/');
    }

    public function testDownload()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/shop/download/');
    }

    public function testError()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/shop/error');
    }

    public function testConfirm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/shop/confirm');
    }

}
