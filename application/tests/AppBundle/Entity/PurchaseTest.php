<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 22/11/2017
 * Time: 15:10
 */

namespace test\AppBundle\Entity;

use AppBundle\Entity\Card;
use AppBundle\Entity\Purchase;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class PurchaseTest extends TestCase
{
    private $purchase;

    public function __construct()
    {
        parent::__construct();
        $this->purchase = new Purchase();
    }

    public function testCanGetId()
    {
        $this->assertNull($this->purchase->getId());
    }

    public function testCanBeInstanciated()
    {
        $this->assertInstanceOf(Purchase::class, $this->purchase);
    }

    public function testCanSetReference()
    {
        $ref = '0001';
        $this->assertInstanceOf(Purchase::class, $this->purchase->setReference($ref));
        $this->assertEquals($ref, $this->purchase->getReference());
    }

    public function testCanSetQuantity()
    {
        $quantity = 5;
        $this->assertInstanceOf(Purchase::class, $this->purchase->setQuantity($quantity));
        $this->assertEquals($quantity, $this->purchase->getQuantity());
    }

    public function testInsertInvalidQuantity()
    {
        $quantity = 'a';
        $this->expectException(\TypeError::class);
        $this->purchase->setQuantity($quantity);
    }

    public function testCanSetCreatedAt()
    {
        $datetime = new \DateTime();
        $this->assertInstanceOf(Purchase::class, $this->purchase->setCreatedAt($datetime));
        $this->assertEquals($datetime, $this->purchase->getCreatedAt());
    }

    public function testInsertInvalidCreatedAt()
    {
        $datetime = date('d/m/Y');
        $this->expectException(\TypeError::class);
        $this->purchase->setCreatedAt($datetime);
    }

    public function testCanSetUpdatedAt()
    {
        $datetime = new \DateTime();
        $this->assertInstanceOf(Purchase::class, $this->purchase->setUpdatedAt($datetime));
        $this->assertEquals($datetime, $this->purchase->getUpdatedAt());
    }

    public function testInsertInvalidUpdateddAt()
    {
        $datetime = date('d/m/Y');
        $this->expectException(\TypeError::class);
        $this->purchase->setUpdatedAt($datetime);
    }

    public function testCanSetRequester()
    {
        $requester = $this->createMock(User::class);
        $this->assertInstanceOf(Purchase::class, $this->purchase->setRequester($requester));
        $this->assertEquals($requester, $this->purchase->getRequester());
    }

    public function testInsertInvalidRequester()
    {
        $requester = 'no a user';
        $this->expectException(\TypeError::class);
        $this->purchase->setRequester($requester);
    }

    public function testCanSetStatus()
    {
        $status = 'created';
        $this->assertInstanceOf(Purchase::class, $this->purchase->setStatus($status));
        $this->assertEquals($status, $this->purchase->getStatus());
    }

    public function testCanAddCard()
    {
        $card = $this->createMock(Card::class);
        $cards = new ArrayCollection([$card]);

        $this->assertInstanceOf(Purchase::class, $this->purchase->addCard($card));
        $this->assertEquals($cards, $this->purchase->getCards());

    }

    public function testCanRemoveCard()
    {
        $card_1 = $this->createMock(Card::class);
        $card_2 = $this->createMock(Card::class);
        $card_3 = $this->createMock(Card::class);


        $this->purchase->addCard($card_1);
        $this->purchase->addCard($card_2);
        $this->purchase->addCard($card_3);

        $this->purchase->removeCard($card_2);
        $this->purchase->removeCard($card_1);

        $this->assertEquals(1, count($this->purchase->getCards()->toArray()));
    }
}