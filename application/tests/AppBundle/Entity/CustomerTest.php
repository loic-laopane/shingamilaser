<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 22/11/2017
 * Time: 15:10
 */

namespace test\AppBundle\Entity;


use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;


class CustomerTest extends TestCase
{
    private $customer;

    public function __construct()
    {
        parent::__construct();
        $this->customer = new Customer();
    }

    public function testCanGetId()
    {
        $this->assertNull($this->customer->getId());
    }

    public function testCanBeInstanciated()
    {
        $this->assertInstanceOf(Customer::class, $this->customer);
    }

    public function testCanSetFirstName()
    {
        $firstname = 'Jean';
        $this->assertInstanceOf(Customer::class, $this->customer->setFirstname($firstname));
        $this->assertEquals($firstname, $this->customer->getFirstname());
    }

    public function testCanSetLastName()
    {
        $lastname = 'Pierrot';
        $this->assertInstanceOf(Customer::class, $this->customer->setLastname($lastname));
        $this->assertEquals($lastname, $this->customer->getLastname());
    }

    public function testCanSetNickName()
    {
        $nickname = 'JJ';
        $this->assertInstanceOf(Customer::class, $this->customer->setNickname($nickname));
        $this->assertEquals($nickname, $this->customer->getNickname());
    }

    public function testCanSetSociety()
    {
        $society = 'Shinigami';
        $this->assertInstanceOf(Customer::class, $this->customer->setSociety($society));
        $this->assertEquals($society, $this->customer->getSociety());
    }

    public function testCanSetAddress()
    {
        $address = 'Rue de Bourgogne';
        $this->assertInstanceOf(Customer::class, $this->customer->setAddress($address));
        $this->assertEquals($address, $this->customer->getAddress());
    }

    public function testCanSetZipCode()
    {
        $zipcode = 75321;
        $this->assertInstanceOf(Customer::class, $this->customer->setZipcode($zipcode));
        $this->assertEquals($zipcode, $this->customer->getZipcode());
    }

    public function testInsertInvalidZipCode()
    {
        $zipcode = 546;
        $this->expectException(\InvalidArgumentException::class);
        $this->customer->setZipcode($zipcode);
    }

    public function testCanSetCity()
    {
        $city = 'MyTown';
        $this->assertInstanceOf(Customer::class, $this->customer->setCity($city));
        $this->assertEquals($city, $this->customer->getCity());
    }

    public function testCanBirthdate()
    {
        $datetime = new \DateTime();
        $this->assertInstanceOf(Customer::class, $this->customer->setBirthdate($datetime));
        $this->assertEquals($datetime, $this->customer->getBirthdate());
    }

    public function testInsertInvalidBirthdate()
    {
        $datetime = date('d/m/Y');
        $this->expectException(\TypeError::class);
        $this->customer->setBirthdate($datetime);

    }

    public function testCanSetUser()
    {
        $user = $this->createMock(User::class);
        $this->assertInstanceOf(Customer::class, $this->customer->setUser($user));
        $this->assertEquals($user, $this->customer->getUser());
    }

    public function testInsertInvalidUser()
    {
        $this->expectException(\TypeError::class);
        $this->customer->setUser('bad_user_class');
    }

    public function testCanAddCard()
    {
        $card = $this->createMock(Card::class);
        $cards = new ArrayCollection([$card, $card]);
        $this->assertInstanceOf(Customer::class, $this->customer->addCard($card));

        $this->customer->addCard($card);
        $this->assertEquals($cards, $this->customer->getCards());
    }

    public function testCanRemoveCard()
    {
        $card_1 = $this->createMock(Card::class);
        $card_2 = $this->createMock(Card::class);
        $card_3 = $this->createMock(Card::class);


        $this->customer->addCard($card_1);
        $this->customer->addCard($card_2);
        $this->customer->addCard($card_3);

        $this->customer->removeCard($card_2);
        $this->customer->removeCard($card_1);

        $this->assertEquals(1, count($this->customer->getCards()->toArray()));
    }

}