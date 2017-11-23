<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 22/11/2017
 * Time: 15:10
 */

namespace test\AppBundle\Entity;

use AppBundle\Entity\Center;
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


    public function testInsertInvalidEmail()
    {
        $email = 'aaa-aaa.com';
        $this->expectException(\InvalidArgumentException::class);
        $this->user->setEmail($email);
    }


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

    public function testCanSetRole()
    {
        $roles = ['ROLE_USER', 'ROLE_STAFF'];
        $this->assertInstanceOf(User::class, $this->user->setRoles($roles));
        $this->assertEquals($roles, $this->user->getRoles());
    }

    public function testInsertInvalidRole()
    {
        $roles = 'ROLE_USER';
        $this->expectException(\InvalidArgumentException::class);
        $this->user->setRoles($roles);
    }

    public function testCanSetUserActive()
    {
        $active = true;
        $this->assertInstanceOf(User::class, $this->user->setActive($active));
        $this->assertEquals($active, $this->user->getActive());
    }

    public function testFailedSetUserActive()
    {
        $active = 'd';
        $this->expectException(\InvalidArgumentException::class);
        $this->user->setActive($active);
    }

    public function testCanSetCenter()
    {
        $center = $this->createMock(Center::class);
        $this->assertInstanceOf(User::class, $this->user->setCenter($center));
        $this->assertEquals($center, $this->user->getCenter());
    }

    public function testSetInvalidCenter()
    {
        $center = 'not a center object';
        $this->expectException(\TypeError::class);
        $this->user->setCenter($center);
    }

    public function testCanSetSalt()
    {
        $salt = '';
        $this->assertInstanceOf(User::class, $this->user->setSalt($salt));
        $this->assertEquals($salt, $this->user->getSalt());
    }

    public function testCanEraseCredential()
    {
        $this->assertInstanceOf(User::class, $this->user->eraseCredentials());
    }
}