<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PurchaseControllerTest extends WebTestCase
{
    public function testRequest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/purchase/request');
    }

    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/purchase/show');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/purchase/list');
    }

}
