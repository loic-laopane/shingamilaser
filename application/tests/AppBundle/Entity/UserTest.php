<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 22/11/2017
 * Time: 15:10
 */

namespace test\AppBundle\Entity;

use AppBundle\Entity\User;

use PHPUnit\Framework\TestCase;


class UserTest extends TestCase
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    public function testCanGetId()
    {
        $this->assertNull($this->user->getId());
    }

    public function testCanBeInstanciated()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    public function testCanSetUserName()
    {
        $username = 'user_name';
        $this->assertInstanceOf(User::class, $this->user->setUsername($username));
        $this->assertEquals($username, $this->user->getUsername());
    }

    public function testCanSetPassword()
    {
        $password = 'azerty';
        $this->assertInstanceOf(User::class, $this->user->setPassword($password));
        $this->assertEquals($password, $this->user->getPassword());
    }

    public function testCanSetEmail()
    {
        $email = 'aaa@aaa.com';
        $this->assertInstanceOf(User::class, $this->user->setEmail($email));
        $this->assertEquals($email, $this->user->getEmail());
    }

    /*
    public function testInsertInvalidEmail()
    {
        $email = 'aaa-aaa.com';
        $this->expectException(\TypeError::class);
        $this->user->setEmail($email);
    }
    */

    public function testCanCreatedAt()
    {
        $datetime = new \DateTime();
        $this->assertInstanceOf(User::class, $this->user->setCreatedAt($datetime));
        $this->assertEquals($datetime, $this->user->getCreatedAt());
    }

    public function testInsertInvalidCreatedAt()
    {
        $datetime = date('d/m/Y');
        $this->expectException(\TypeError::class);
        $this->user->setCreatedAt($datetime);

    }

}