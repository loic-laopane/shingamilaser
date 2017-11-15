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
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

class CustomerManagerTest extends TestCase
{
    private $customerManager;
    private $repository;
    private $userManager;
    private $objectManager;
    private $customer;
    private $user;

    public function __construct()
    {
        parent::__construct();

        $this->repository = $this->getMockBuilder(ObjectRepository::class)->getMock();
        $this->objectManager = $this->getMockBuilder(ObjectManager::class)->getMock();
        //$this->objectManager->method('getRepository')->with($this->equalTo(ObjectRepository::class));
        $this->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $this->customerManager = new CustomerManager($this->objectManager, $this->userManager);
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
        $this->objectManager->expects($this->once())->method('persist')->with($this->equalTo($this->customer));
        $this->objectManager->expects($this->once())->method('flush');
        $this->assertInstanceOf(CustomerManager::class, $this->customerManager->register($this->customer));
    }

    public function testCanSave()
    {
        $this->objectManager->expects($this->once())->method('persist')->with($this->equalTo($this->customer));
        $this->objectManager->expects($this->once())->method('flush');
        $this->assertInstanceOf(CustomerManager::class, $this->customerManager->register($this->customer));
    }

}