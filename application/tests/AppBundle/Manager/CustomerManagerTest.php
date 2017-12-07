<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:10
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use AppBundle\Manager\CustomerManager;
use AppBundle\Manager\UserManager;
use AppBundle\Repository\CustomerRepository;
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

}