<?php

use AppBundle\Entity\User;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Behat\Hook\Call\BeforeScenario;
use Behat\Behat\Hook\Call\AfterScenario;


/**
 * Defines application features from the specific context.
 */
class UserContext extends MinkContext
{
    use KernelDictionary;
    private $user;

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
     * @Given I am on the dashboard page
     */
    public function iAmOnTheDashboardPage()
    {
        $this->visitPath('/admin');
    }

    /**
     * @Then I should be on the users list page
     */
    public function iShouldBeOnTheUsersListPage()
    {
        $this->visitPath('/admin/users');
    }

    /**
     * @Given I am on the users list page
     */
    public function iAmOnTheUsersListPage()
    {
        $this->visitPath('/admin/users');
    }

    /**
     * @Then I should be on the user edit page
     */
    public function iShouldBeOnTheUserEditPage()
    {
        $this->visitPath('/admin/user/'.$this->user->getId().'/edit');
    }

    /**
     * @BeforeScenario @fake_user
     */
    public function createUser()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $username = 'fakeUser';
        $plainPassword = $username;
        $email = $username.'@'.$username.'.test';

        $this->user = new User();
        $this->user->setUsername($username)
            ->setEmail($email)
            ->setRoles(['ROLE_USER']);
        $password = $this->getContainer()->get('security.password_encoder')->encodePassword($this->user, $plainPassword);
        $this->user->setPassword($password);

        $em->persist($this->user);
        $em->flush();

    }

    /**
     * @AfterScenario @fake_user
     */
    public function deleteUser()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        //$em->getRepository(User::class)->findOneBy(['username' => $this->user->getUsername()]);
        $em->remove($this->user);
        $em->flush();
    }
}
