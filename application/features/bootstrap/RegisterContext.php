<?php

use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Behat\Hook\Call\BeforeScenario;
use Behat\Behat\Hook\Call\AfterScenario;

/**
 * Defines application features from the specific context.
 */
class RegisterContext extends MinkContext
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
     * @Then I should be on the register page
     */
    public function iShouldBeOnTheRegisterPage()
    {
        $this->visitPath('/register');
    }

    /**
     * @Given I am on the register page
     */
    public function iAmOnTheRegisterPage()
    {
        $this->visitPath('/register');
    }

    /**
     * @When I correctly fill all fields form
     */
    public function iFillAllTheRegisterForm()
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $page->fillField('firstname', 'fake_firstname');
        $page->fillField('lastname', 'fake_lastname');
        $page->fillField('nickname', 'fake_nickname');
        $page->fillField('user_username', 'fake_username');
        $page->fillField('user_password_first', 'fake_password');
        $page->fillField('user_password_second', 'fake_password');
        $page->fillField('user_email', 'fake_email@email.com');
    }


    /**
     * @AfterScenario @remove_registered_customer
     */
    public function deleteRegisteredUserAndCustomer()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $customer = $em->getRepository(Customer::class)->findOneBy(['nickname' => 'fake_nickname']);
        if(!$customer instanceof Customer)
        {
            throw new Exception('Cannot found the fake customer');
        }
        $user = $customer->getUser();
        if(!$user instanceof User)
        {
            throw new Exception('Cannot found the fake user linked to the fake customer');
        }
        $em->remove($customer);
        $em->flush();
    }
}
