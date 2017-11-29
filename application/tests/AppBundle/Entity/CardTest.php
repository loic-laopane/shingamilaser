<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 22/11/2017
 * Time: 14:11
 */

namespace test\AppBundle\Entity;


use AppBundle\Entity\Card;
use AppBundle\Entity\Center;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Purchase;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{

    private $card;

    public function __construct()
    {
        parent::__construct();
        $this->card = new Card();
    }

    public function testCanGetId()
    {
        $this->assertNull($this->card->getId());
    }

    public function testCanBeInstanciated()
    {
        $this->assertInstanceOf(Card::class, $this->card);
    }


    public function testCanSetCode()
    {
        $code = '123456';

        $this->assertInstanceOf(Card::class, $this->card->setCode($code));
        $this->assertEquals($code, $this->card->getCode());
    }

    public function testCannotSetCode()
    {
        $code = '12';
        $this->expectException(\InvalidArgumentException::class);
        $this->card->setCode($code);
    }

    public function testCanSetNumero()
    {
        $numero = 1234567890;

        $this->assertInstanceOf(Card::class, $this->card->setNumero($numero));
        $this->assertEquals($numero, $this->card->getNumero($numero));
    }

    public function testCannotSetNumero()
    {
        $numero = '123';

        $this->expectException(\InvalidArgumentException::class);
        $this->card->setNumero($numero);
    }

    public function testCanSetActivatedAt()
    {
        $datetime = $this->createMock(\DateTime::class);
        $this->assertInstanceOf(Card::class, $this->card->setActivatedAt($datetime));
        $this->assertEquals($datetime, $this->card->getActivatedAt());
    }

    public function testCannotSetActivatedAt()
    {
        $date = date('d/m/Y');
        $this->expectException(\TypeError::class);
        $this->card->setActivatedAt($date);
    }

    public function testCardHasOwner()
    {
        $customer = $this->createMock(Customer::class);
        $this->card->setCustomer($customer);
        $this->assertInstanceOf(Customer::class, $this->card->hasOwner());

    }

    public function testCardHasNotOwner()
    {
        $this->assertNull($this->card->hasOwner());
    }


    public function testCanSetActive()
    {
        $this->assertInstanceOf(Card::class, $this->card->setActive(true));
        $this->assertTrue($this->card->getActive());
    }

    public function testCanSetCenter()
    {
        $center = $this->createMock(Center::class);
        $this->assertInstanceOf(Card::class, $this->card->setCenter($center));
        $this->assertInstanceOf(Center::class, $this->card->getCenter());
    }

    public function testCanSetCustomer()
    {
        $customer = $this->createMock(Customer::class);
        $this->assertInstanceOf(Card::class, $this->card->setCustomer($customer));
        $this->assertInstanceOf(Customer::class, $this->card->getCustomer());
    }

    public function testCanSetPurchase()
    {
        $purchase = $this->createMock(Purchase::class);
        $this->assertInstanceOf(Card::class, $this->card->setPurchase($purchase));
        $this->assertInstanceOf(Purchase::class, $this->card->getPurchase());
    }

}