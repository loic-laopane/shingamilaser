<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;


/**
 * Defines application features from the specific context.
 */
class DashboardContext extends MinkContext
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
     * @Then I should be on the admin dashboard page
     */
    public function iShouldBeOnTheAdminDashboardPage()
    {
        //$this->visitPath($this->getContainer()->get('router')->generate('admin'));
        $this->visitPath('/admin');
    }
}
