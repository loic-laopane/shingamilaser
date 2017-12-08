<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\Card;
use AppBundle\Entity\Center;
use AppBundle\Entity\Purchase;
use AppBundle\Entity\User;
use AppBundle\Manager\PurchaseManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\Workflow\Workflow;


class PurchaseManagerTest extends TestCase
{
    private $objectManager;
    private $workflow;
    private $session;
    private $dispatcher;
    private $serializer;
    private $uri;

    public function setUp()
    {
        parent::setUp();

        $this->workflow = $this->createMock(Workflow::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->objectManager = $this->createMock(ObjectManager::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->uri = 'http://localhost/web/app_dev.php/api/';

    }

    public function testCanSave()
    {
        $purchase = $this->createMock(Purchase::class);
        $purchaseManager = new PurchaseManager($this->uri, $this->workflow, $this->objectManager, $this->session, $this->dispatcher, $this->serializer);
        $this->assertEquals($purchaseManager, $purchaseManager->save($purchase));
    }

    public function testCanCreate()
    {
        $purchase = $this->createMock(Purchase::class);
        $purchaseManager = new PurchaseManager($this->uri, $this->workflow, $this->objectManager, $this->session, $this->dispatcher, $this->serializer);
        $this->assertEquals($purchaseManager, $purchaseManager->create($purchase));
    }

    public function testCanSearch()
    {
        $numero = 987;
        $purchase = $this->createMock(Purchase::class);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->method('findOneBy')->with(['numero' => $numero])->willReturn($purchase);
        $this->objectManager->method('getRepository')->willReturn($repo);
        $purchaseManager = new PurchaseManager($this->uri, $this->workflow, $this->objectManager, $this->session, $this->dispatcher, $this->serializer);
        $this->assertEquals($purchase, $purchaseManager->search($numero));
    }

    public function testMakeRequest()
    {
        $center = $this->createMock(Center::class);

        $user = $this->createMock(User::class);
        $user->method('getCenter')->willReturn($center);

        $purchase = $this->createMock(Purchase::class);
        $purchase->method('getRequester')->willReturn($user);
        $purchase->method('getQuantity')->willReturn(1);

        $purchaseManager = new PurchaseManager($this->uri, $this->workflow, $this->objectManager, $this->session, $this->dispatcher, $this->serializer);
        $this->assertEquals($purchaseManager, $purchaseManager->makeRequest($purchase));
    }

    public function testCanGetCards()
    {
        $card = $this->createMock(Card::class);
        $api_card = ['code' => '456789', 'numero'=> 1234567980];
        $cards = [$api_card, $api_card];

        $center = $this->createMock(Center::class);
        $center->method('getCode')->willReturn(123);

        $user = $this->createMock(User::class);
        $user->method('getCenter')->willReturn($center);

        $purchase = $this->createMock(Purchase::class);
        $purchase->method('getRequester')->willReturn($user);
        $purchase->method('getQuantity')->willReturn(1);

        $data = array('status_code' => 200,
                        'message' => '',
                        'cards' => $cards);
        //$this->serializer->method('deserialize')->willReturn($data);
        //$this->workflow->method('can')->willReturn(true);


        $purchaseManager = new PurchaseManager($this->uri, $this->workflow, $this->objectManager, $this->session, $this->dispatcher, $this->serializer);
        $this->assertEquals($purchaseManager, $purchaseManager->getCards($purchase));
    }

}
