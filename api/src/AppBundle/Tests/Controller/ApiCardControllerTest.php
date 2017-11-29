<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiCardControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/list');
    }

    public function testGet()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/get');
    }

    public function testRequest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'api/request');
    }

}
