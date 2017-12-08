<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use AppBundle\Manager\CardManager;
use AppBundle\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

class CardManagerTest extends TestCase
{
    private $objectManager;
    private $userManager;
    private $session;
    private $card;

    public function setUp()
    {
        parent::setUp();

        $this->repository = $this->getMockBuilder(ObjectRepository::class)->getMock();
        $this->objectManager = $this->createMock(ObjectManager::class);
        $this->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $this->card = $this->createMock(Card::class);
        $this->customer = $this->createMock(Customer::class);

    }

    public function testCanSave()
    {
        $cardManager = new CardManager($this->objectManager);
        $this->objectManager->expects($this->once())->method('persist')->with($this->equalTo($this->card));
        $this->objectManager->expects($this->once())->method('flush');
        $this->assertEquals($cardManager, $cardManager->save($this->card));
    }

    public function testCanAttachCardToCustomer()
    {
        $cardManager = new CardManager($this->objectManager, $this->session);
        $this->card->expects($this->once())->method('setCustomer')->with($this->customer);
        $this->assertEquals($cardManager, $cardManager->attach($this->card, $this->customer));
    }

    public function testSearchReturnResult()
    {

        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn($this->customer);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $cardManager = new CardManager($this->objectManager);
        $this->assertInstanceOf(Customer::class, $cardManager->search($this->getNumero()));
    }

    public function testCheckUserIsGoodUser()
    {
        $user = $this->createMock(User::class);
        $this->customer->method('getUser')->willReturn($user);
        $this->card->method('getCustomer')->willReturn($this->customer);
        $cardManager = new CardManager($this->objectManager);
        $this->assertEquals($cardManager, $cardManager->checkUser($this->card, $user));
    }

    public function testCheckUserIsBadUser()
    {
        $user = $this->createMock(User::class);
        $this->card->method('getCustomer')->willReturn($this->customer);
        $cardManager = new CardManager($this->objectManager);
        $this->expectException(\Exception::class);
        $cardManager->checkUser($this->card, $user);
    }

    public function testCheckUserWithBadCard()
    {
        $user = $this->createMock(User::class);
        $this->card->method('getCustomer')->willReturn(null);
        $cardManager = new CardManager($this->objectManager);
        $this->expectException(\Exception::class);
        $cardManager->checkUser($this->card, $user);
    }

    public function testCanRattachNumeroToCustomer()
    {
        $cards = [$this->card, $this->card];
        $this->customer->method('getCards')->willReturn($cards);
        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn($this->card);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $cardManager = new CardManager($this->objectManager);

        $this->assertEquals($cardManager, $cardManager->rattach($this->getNumero(), $this->customer));
    }

    public function testCardDoNotExistOnRattach()
    {
        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn(null);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $cardManager = new CardManager($this->objectManager);

        $this->expectException(\Exception::class);
        $cardManager->rattach($this->getNumero(), $this->customer);
    }

    public function testCardAlreadyAttachedtOnRattach()
    {
        $this->card->method('hasOwner')->willReturn(true);
        $this->card->method('getCustomer')->willReturn($this->customer);
        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn($this->card);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $cardManager = new CardManager($this->objectManager);

        $this->expectException(\Exception::class);
        $cardManager->rattach($this->getNumero(), $this->customer);
    }

    public function testCardHasAlreadyOwnerOnRattach()
    {
        $otherCustomer = $this->createMock(Customer::class);
        $this->card->method('hasOwner')->willReturn(true);
        $this->card->method('getCustomer')->willReturn($otherCustomer);
        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn($this->card);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $cardManager = new CardManager($this->objectManager);

        $this->expectException(\Exception::class);
        $cardManager->rattach($this->getNumero(), $this->customer);
    }

    public function testCardNotActiveOnRattach()
    {
        $otherCustomer = $this->createMock(Customer::class);
        $this->card->method('getActive')->willReturn(false);
        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn($this->card);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $cardManager = new CardManager($this->objectManager);

        $this->expectException(\Exception::class);
        $cardManager->rattach($this->getNumero(), $this->customer);
    }

    private function getNumero()
    {
        return 1231234567;
    }
}
