<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profile/show');
    }

    public function testEdituser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/account/edit');
    }

    public function testEditcustomer()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/account/profile/edit');
    }

    public function testAddcard()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/account/cards/add');
    }

    public function testShowoffers()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/account/offers');
    }
}
