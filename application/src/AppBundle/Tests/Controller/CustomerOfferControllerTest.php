<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerOfferControllerTest extends WebTestCase
{
    public function testCheckoffer()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/staff/customer/{id}/checkoffer/{customer_offer_id}');
    }

}
