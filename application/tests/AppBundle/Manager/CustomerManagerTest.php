<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:10
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Game;
use AppBundle\Entity\Image;
use AppBundle\Entity\Offer;
use AppBundle\Entity\User;
use AppBundle\Manager\CustomerManager;
use AppBundle\Manager\UserManager;
use AppBundle\Repository\CardRepository;
use AppBundle\Repository\CustomerOfferRepository;
use AppBundle\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CustomerManagerTest extends TestCase
{
    private $customerManager;
    private $repository;
    private $userManager;
    private $objectManager;
    private $customer;
    private $user;
    private $dispatcher;

    public function setUp()
    {
        parent::setUp();

        $this->repository = $this->getMockBuilder(ObjectRepository::class)->getMock();
        $this->objectManager = $this->getMockBuilder(ObjectManager::class)->getMock();
        $this->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $this->dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $this->user = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();

        $customer = $this->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUser'])
            ->getMock();
        $customer->method('getUser')->willReturn($this->user);
        $this->customer = $customer;

    }

    //register
    public function testCanRegister()
    {
        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);

        $this->assertInstanceOf(CustomerManager::class, $customerManage->register($this->customer));
    }

    public function testEmailAlreadyUsedOnRegister()
    {
        $this->userManager->expects($this->once())->method('mailExists')->willReturn(true);
        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);
        $this->expectException(\Exception::class);
        $customerManage->register($this->customer);
    }

    public function testCanSave()
    {
        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);

        $this->assertInstanceOf(CustomerManager::class, $customerManage->save($this->customer));
    }

    public function testGetCustomerByUser()
    {
        $user = $this->createMock(User::class);
        $repo = $this->createMock(CustomerRepository::class);
        $repo->expects($this->once())->method('findOneBy')->with(['user' => $user])->willReturn($this->customer);
        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repo);
        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);

        $this->assertInstanceOf(Customer::class, $customerManage->getCustomerByUser($user));
    }


    public function testGetCustomerCards()
    {
        $card = $this->createMock(Card::class);

        $cards = [$card, $card];
        $repo = $this->createMock(ObjectRepository::class);
        $repo->expects($this->any())->method('findBy')->with(['customer' => $this->customer])->willReturn($cards);
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->method('getRepository')->willReturn($repo);

        $customerManage = new CustomerManager($objectManager, $this->userManager, $this->dispatcher);

        $this->assertEquals($cards, $customerManage->getCustomerCards($this->customer));
    }

    public function testGetCustomerEmptyCards()
    {
        $repo = $this->createMock(ObjectRepository::class);
        $repo->expects($this->any())->method('findBy')->with(['customer' => $this->customer])->willReturn(null);
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->method('getRepository')->willReturn($repo);

        $customerManage = new CustomerManager($objectManager, $this->userManager, $this->dispatcher);

        $this->assertEquals(new ArrayCollection(), $customerManage->getCustomerCards($this->customer));
    }

    public function testGetCustomerByParams()
    {
        $params  = [];
        $customers = [$this->customer, $this->customer];
        $repo = $this->createMock(CustomerRepository::class);
        $repo->expects($this->any())->method('findByParams')->with($params)->willReturn($customers);
        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repo);

        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);

        $this->assertEquals($customers, $customerManage->getCustomerByParams($params));
    }

    public function testNotRemoveAvatar() {
        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);
        $this->assertEquals($customerManage, $customerManage->removeAvatar($this->customer));
    }

    public function testRemoveAvatar() {
        $avatar = $this->createMock(Image::class);
        $this->customer = $this->createMock(Customer::class);
        $this->customer->method('getAvatar')->willReturn($avatar);
        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);
        $this->assertEquals($customerManage, $customerManage->removeAvatar($this->customer));
    }

    public function testQuickCreateUser()
    {
        $user = $this->createMock(User::class);
        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);
        $this->assertEquals($customerManage, $customerManage->quickCreate($this->customer, $user));
    }

    public function testGetCountUnlockOffer() {
        $nbUnlockedOffers = 5;
        $offer = $this->createMock(Offer::class);

        $repo = $this->createMock(CustomerOfferRepository::class);
        $repo->method('getCountUnlockOffer')->willReturn($nbUnlockedOffers);
        $this->objectManager->method('getRepository')->willReturn($repo);

        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);
        $this->assertEquals($nbUnlockedOffers, $customerManage->getCountUnlockOffer($this->customer, $offer));
    }

    public function testUnlockOffer() {
        $game = $this->createMock(Game::class);
        $offer = $this->createMock(Offer::class);
        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);
        $this->assertEquals($customerManage, $customerManage->unlockOffer($this->customer, $offer, $game));
    }

    public function testCheckBadSearchParams() {
        $params  = [];
        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);
        $this->expectException(\Exception::class);
         $customerManage->checkSearchParams($params);
    }

    public function testCheckSearchParams() {
        $params  = ['param 1'];
        $customerManage = new CustomerManager($this->objectManager, $this->userManager, $this->dispatcher);
        $this->assertEquals(null, $customerManage->checkSearchParams($params));
    }
}