<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AccountControllerTest
{

    private $client = null;
    private $container;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->container = static::$kernel->getContainer();
    }

    public function testShow()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/account/profile/edit');


        // the HttpKernel response instance
//        $response = $client->getResponse();

        // the BrowserKit response instance
        //        $response = $client->getInternalResponse();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        //$this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }

    private function logIn()
    {
        $session = $this->container->get('session');

        // the firewall context defaults to the firewall name
        $firewallContext = 'main';

        $token = new UsernamePasswordToken(new User(), null, $firewallContext, array('ROLE_USER'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

}
