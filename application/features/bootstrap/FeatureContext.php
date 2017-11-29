<?php

use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Behat\Hook\Call\BeforeScenario;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{

    use KernelDictionary;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {

    }


    /**
     * @Given I am authenticated as :username
     */
    public function iAmAuthenticatedAs($username)
    {

        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof BrowserKitDriver) {
            throw new UnsupportedDriverActionException('This step is only supported by the BrowserKitDriver', $driver);
        }

        $client = $driver->getClient();
        $client->getCookieJar()->set(new Cookie(session_name(), true));

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $session = $this->getContainer()->get('session');


        $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        $providerKey = 'main';

        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->getContainer()->get('security.token_storage')->setToken($token);
        $session->set('_security_'.$providerKey, serialize($token));
        $session->save();



        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

    }

    /**
     * @Given I am authenticated as :username with password :password
     */
    public function iAmAuthenticatedAsWithPassword($username, $password)
    {
        $session = $this->getSession();

        $page = $session->getPage();
        $session->visit('/login');
        $page->fillField('username', $username);
        $page->fillField('password', $password);
        $page->pressButton('btn.login');

}




}
