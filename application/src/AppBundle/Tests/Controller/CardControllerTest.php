<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardControllerTest extends WebTestCase
{
    public function testRequest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/card/request');
    }

    public function testGet()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'card/get');
    }

    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/search');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cardst');
    }
}
