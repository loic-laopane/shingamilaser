<?php

use AppBundle\Entity\Center;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Behat\Hook\Call\BeforeScenario;
use Behat\Behat\Hook\Call\AfterScenario;


/**
 * Defines application features from the specific context.
 */
class CenterContext extends MinkContext
{
    use KernelDictionary;
    private $center;
    private $center_api;

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
     * @Then I should be on the centers list page
     */
    public function iShouldBeOnTheCentersListPage()
    {
        $this->visitPath('/admin/centers');
    }

    /**
     * @Given I am on the centers list page
     */
    public function iAmOnTheCentersListPage()
    {
        $this->visitPath('/admin/centers');
    }

    /**
     * @Then I should be on the center create page
     */
    public function iShouldBeOnTheCenterCreatePage()
    {
        $this->visitPath('/admin/center/create');
    }

    /**
     * @Then I should be on the center edit page
     */
    public function iShouldBeOnTheCenterEditPage()
    {
        $this->visitPath('/admin/center/'.$this->center->getId().'/edit');
    }

    /**
     * @BeforeScenario
     * @fake_center
     */
    public function createCenter()
    {
        $default_manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->center = new Center();
        $this->center->setName('Fake Center')
                    ->setCode(999)
                    ->setAddress('No addresss')
                    ->setZipcode(99999)
                    ->setCity('No city');

        $default_manager->persist($this->center);
        $default_manager->flush();


        $api_manager = $this->getContainer()->get('doctrine.orm.api_entity_manager');
        $this->center_api = new \Wf3\ApiBundle\Entity\Center();
        $this->center_api->setName($this->center->getName())
                        ->setCode($this->center->getCode())
                        ->setAddress($this->center->getAddress())
                        ->setZipcode($this->center->getZipcode())
                        ->setCity($this->center->getCity())
        ;


        $api_manager->persist($this->center_api);
        $api_manager->flush();

    }

    /**
     * @AfterScenario
     * @fake_center
     */
    public function deleteCenter()
    {
        $default_manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $api_manager = $this->getContainer()->get('doctrine.orm.api_entity_manager');
        $default_manager->remove($this->center);
        $default_manager->flush();

        $api_manager->remove($this->center_api);
        $api_manager->flush();

    }

}
