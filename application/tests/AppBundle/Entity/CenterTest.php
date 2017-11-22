<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 22/11/2017
 * Time: 15:10
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class CenterTest extends TestCase
{
    private $center;

    public function __construct()
    {
        parent::__construct();
        $this->center = new Center();
    }

    public function testCanGetId()
    {
        $this->assertNull($this->center->getId());
    }

    public function testCanBeInstanciated()
    {
        $this->assertInstanceOf(Center::class, $this->center);
    }

    public function testCanSetCode()
    {
        $code = 123;
        $this->assertInstanceOf(Center::class, $this->center->setCode($code));
        $this->assertEquals($code, $this->center->getCode());
    }

    public function testInsertInvalidCode()
    {
        $code = 1234;
        $this->expectException(\InvalidArgumentException::class);
        $this->center->setCode($code);
    }

    public function testCanSetName()
    {
        $name = 'Center Shishi';
        $this->assertInstanceOf(Center::class, $this->center->setName($name));
        $this->assertEquals($name, $this->center->getName());
    }

    public function testCanSetAddress()
    {
        $address = 'Rue de Bourgogne';
        $this->assertInstanceOf(Center::class, $this->center->setAddress($address));
        $this->assertEquals($address, $this->center->getAddress());
    }

    public function testCanSetZipCode()
    {
        $zipcode = 75321;
        $this->assertInstanceOf(Center::class, $this->center->setZipcode($zipcode));
        $this->assertEquals($zipcode, $this->center->getZipcode());
    }

    public function testInsertInvalidZipCode()
    {
        $zipcode = 546;
        $this->expectException(\InvalidArgumentException::class);
        $this->center->setZipcode($zipcode);
    }

    public function testCanSetCity()
    {
        $city = 'MyTown';
        $this->assertInstanceOf(Center::class, $this->center->setCity($city));
        $this->assertEquals($city, $this->center->getCity());
    }

    public function testCanAdduser()
    {
        $user = $this->createMock(User::class);
        $users = new ArrayCollection([$user, $user]);
        $this->assertInstanceOf(Center::class, $this->center->addUser($user));

        $this->center->addUser($user);
        $this->assertEquals($users, $this->center->getUsers());
    }


    public function testCanRemoveuser()
    {
        $user_1 = $this->createMock(User::class);
        $user_2 = $this->createMock(User::class);
        $user_3 = $this->createMock(User::class);
        $users = new ArrayCollection([$user_1, $user_3]);

        $this->center->addUser($user_1);
        $this->center->addUser($user_2);
        $this->center->addUser($user_3);

        $this->center->removeUser($user_1);



        $this->assertEquals(2, count($this->center->getUsers()->toArray()));
    }
}