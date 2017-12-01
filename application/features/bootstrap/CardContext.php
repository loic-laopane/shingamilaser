<?php

use AppBundle\Entity\Game;
use AppBundle\Entity\Purchase;
use AppBundle\Entity\User;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Behat\Hook\Call\BeforeScenario;
use Behat\Behat\Hook\Call\AfterScenario;

/**
 * Defines application features from the specific context.
 */
class CardContext extends MinkContext
{
    use KernelDictionary;

    private $purchase;
    private $purchase_list;


    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->purchase = new Purchase();
    }

    /**
     * @Given I am on the cards purchase page
     * @Then I should be on the cards purchase page
     */
    public function iAmOnTheCardsPurchasePage()
    {
        $this->visitPath('/staff/purchase/create');
    }

    /**
     * @Then I should be on the cards purchase list
     */
    public function iShouldBeOnTheCardsPurchaseList()
    {
        $this->visitPath('/staff/purchase/list');
    }

    /**
     * @Given I am on the edit cards purchase page
     */
    public function iAmOnTheEditCardsPurchasePage()
    {
        $this->visitPath('/staff/purchase/'.$this->purchase->getId().'/edit');
    }

    /**
     * @AfterScenario @delete_last_purchase
     */
    public function deleteLastPurchaseTest()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $purchase = $em->getRepository(Purchase::class)->findOneBy([], ['id' => 'desc'], 1);
        if(!$purchase instanceof Purchase)
        {
            throw new Exception('Cannot delete the purchase');
        }

        $em->remove($purchase);
        $em->flush();
    }

    /**
     * @BeforeScenario @fake_purchase
     */
    public function createPurchase()
    {
        //print_r(FeatureContext::getUser());
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $current_user = $em->getRepository(User::class)->findOneBy([], ['id' => 'desc'], 1);
        if(!$current_user instanceof User)
        {
            throw new Exception('Cannot get the current user logged in');
        }
        $this->purchase->setQuantity(2);
        $this->purchase->setRequester($current_user);
        $em->persist($this->purchase);
        $em->flush();
    }

    /**
     * @AfterScenario @fake_purchase
     */
    public function deletePurchase()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->remove($this->purchase);
        $em->flush();
    }



    /**
     * @Given I am on the cards purchase list
     */
    public function iAmOnTheCardsPurchaseList()
    {
        $this->visitPath('/staff/purchase/list');
    }

    /**
     * @Given Purchases exist
     */
    public function purchasesExist()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $current_user = $em->getRepository(User::class)->findOneBy([], ['id' => 'desc'], 1);
        $purchase = new Purchase();
        $purchase->setRequester($current_user)
                ->setQuantity(1)
                ;
        
        $this->purchase_list = array($purchase, $purchase);
        foreach($this->purchase_list as $purchase)
        {
            $em->persist($purchase);
        }
        $em->flush();

    }

    /**
     * @AfterScenario @remove_purchase_list
     */
    public function removePurchaseList()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        foreach ($this->purchase_list as $purchase)
        {
            $em->remove($purchase);
        }
        $em->flush();
    }

}
