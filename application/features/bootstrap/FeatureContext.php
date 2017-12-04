<?php

use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Definition\Call\Given;
use Behat\Behat\Hook\Call\BeforeScenario;
use Behat\Behat\Hook\Call\AfterScenario;
/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{

    use KernelDictionary;
    private $user;
    private $customer;
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
     * @BeforeScenario @login_user
     */
    public function createUserAsUser()
    {
        $this->createUser('ROLE_USER');
        $this->createCustomer();
    }

    /**
     * @BeforeScenario @login_staff
     */
    public function createUserAsStaff()
    {
        $this->createUser('ROLE_STAFF');
    }

    /**
     * @BeforeScenario @login_admin
     */
    public function createUserAsAdmin()
    {
        $this->createUser('ROLE_ADMIN');
    }
    /**
     * @AfterScenario
     * @login_user
     * @login_staff
     * @login_admin
     */
    public function deleteUser()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        if($em->contains($this->user))
        {
            $em->remove($this->user);
            $em->flush();
        }

    }

    /**
     * @Given I am authenticated as admin
     */

    /**
     * @AfterScenario @login_user
     */
    public function deleteCustomer()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        if($em->contains($this->customer))
        {
            $em->remove($this->customer);
            $em->flush();
        }

    }

    private function createCustomer()
    {
        $name = substr(uniqid(), 0, 7);
        $this->customer = (new Customer())->setNickname($name)
                                        ->setFirstname($name)
                                        ->setLastname($name)
                                        ->setUser($this->user);
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($this->customer);
        $em->flush();
    }

    /**
     * @Given I am authenticated like :role
     */
    public function iAmAuthenticatedLike($role)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $username = substr(uniqid(), 0, 10);
        $plainPassword = $username;
        $email = $username.'@'.$username.'.test';
        $this->user->setUsername($username)
                    ->setEmail($email)
                    ->setRoles([$role]);
        $password = $this->getContainer()->get('security.password_encoder')->encodePassword($this->user, $plainPassword);
        $this->user->setPassword($password);

        $em->persist($this->user);
        $em->flush();

        $this->logIn();


    }

    private function logIn()
    {
        $session = $this->getSession();

        $page = $session->getPage();
        $this->visitPath('/login');
        $page->fillField('username', $this->user->getUsername());
        $page->fillField('password', $this->user->getUsername());
        $page->pressButton('btn.login');
    }

    private function createUser($role)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $username = substr(uniqid(), 0, 10);
        $plainPassword = $username;
        $email = $username.'@'.$username.'.test';

        $this->user = new User();
        $this->user->setUsername($username)
            ->setEmail($email)
            ->setRoles([$role]);
        $password = $this->getContainer()->get('security.password_encoder')->encodePassword($this->user, $plainPassword);
        $this->user->setPassword($password);

        $em->persist($this->user);
        $em->flush();

        $this->logIn();
    }
}
