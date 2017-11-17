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
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Session\Session;

class CardManagerTest extends TestCase
{
    private $objectManager;
    private $userManager;
    private $session;
    private $card;

    public function __construct()
    {
        parent::__construct();

        $this->repository = $this->getMockBuilder(ObjectRepository::class)->getMock();
        $this->objectManager = $this->getMockBuilder(ObjectManager::class)->getMock();
        $this->session = $this->getMockBuilder(Session::class)->getMock();
        $this->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $this->cardManager = new CardManager($this->objectManager, $this->session);
        $this->card = $this->createMock(Card::class);
        $this->customer = $this->createMock(Customer::class);

    }

    public function testCanSave()
    {
        $this->objectManager->expects($this->once())->method('persist')->with($this->equalTo($this->card));
        $this->objectManager->expects($this->once())->method('flush');
        $this->assertInstanceOf(CardManager::class, $this->cardManager->save($this->card));
    }

    public function testCanAttachCardToCustomer()
    {
        $this->card->expects($this->once())->method('setCustomer')->with($this->customer);
        $this->cardManager->attach($this->card, $this->customer);
    }


    public function testCanRattachNumeroToCustomer()
    {

        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn($this->card);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);

        $nativeSessionStorage = $this->getMockBuilder(NativeSessionStorage::class)
            ->setMethods(['add'])
            ->getMock();

        $this->session->expects($this->once())->method('getFlashBag')->willReturn($nativeSessionStorage);
        $cardManager = new CardManager($this->objectManager, $this->session);

        $this->assertTrue($cardManager->rattach($this->getNumero(), $this->customer));
    }

    public function testCardNumberReturnNoCard()
    {

        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn(null);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);

        $nativeSessionStorage = $this->getMockBuilder(NativeSessionStorage::class)
            ->setMethods(['add'])
            ->getMock();

        $this->session->expects($this->once())->method('getFlashBag')->willReturn($nativeSessionStorage);
        $cardManager = new CardManager($this->objectManager, $this->session);

        $this->assertFalse($cardManager->rattach($this->getNumero(), $this->customer));
    }

    public function testCardHasAlreadyOwner()
    {
        $this->card->expects($this->once())->method('hasOwner')->willReturn(true);
        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn($this->card);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);

        $nativeSessionStorage = $this->getMockBuilder(NativeSessionStorage::class)
            ->setMethods(['add'])
            ->getMock();

        $this->session->expects($this->once())->method('getFlashBag')->willReturn($nativeSessionStorage);
        $cardManager = new CardManager($this->objectManager, $this->session);

        $this->assertFalse($cardManager->rattach($this->getNumero(), $this->customer));
    }

    public function testCardIsAlreadyAttachedOnCustomer()
    {
        $this->card->expects($this->once())->method('hasOwner')->willReturn(true);
        $this->card->expects($this->once())->method('getCustomer')->willReturn($this->customer);
        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn($this->card);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);

        $nativeSessionStorage = $this->getMockBuilder(NativeSessionStorage::class)
            ->setMethods(['add'])
            ->getMock();

        $this->session->expects($this->once())->method('getFlashBag')->willReturn($nativeSessionStorage);
        $cardManager = new CardManager($this->objectManager, $this->session);

        $this->assertFalse($cardManager->rattach($this->getNumero(), $this->customer));
    }


    public function testSearchReturnResult()
    {

        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn($this->customer);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $cardManager = new CardManager($this->objectManager, $this->session);
        $this->assertInstanceOf(Customer::class, $cardManager->search($this->getNumero()));
    }

    public function testSearchReturnNoResult()
    {

        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->once())->method('findOneBy')->with(['numero' => $this->getNumero()])->willReturn(null);

        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);
        $cardManager = new CardManager($this->objectManager, $this->session);
        $this->assertNull($cardManager->search($this->getNumero()));
    }



    private function getNumero()
    {
        return 1231234567;
    }
}
