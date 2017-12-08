<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManagerTest extends TestCase
{
    private $objectManager;
    private $encoder;
    private $dispatcher;

    public function setUp()
    {
        parent::setUp();
        $this->objectManager = $this->getMockBuilder(ObjectManager::class)->getMock();

        $this->encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)->getMock();
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
    }

    public function testCanEncodeUserPassword()
    {
        $user = $this->createMock(User::class);
        $user->method('getPassword')->willReturn('test');

        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->assertEquals($userManager, $userManager->encodeUserPassword($user));
    }

    public function testUserExists()
    {
        $pseudo = 'mon pseudo';
        $user = $this->createMock(User::class);
        $user->method('getUsername')->willReturn($pseudo);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->method('findOneBy')->with(['username' => $pseudo])->willReturn($user);
        $this->objectManager->method('getRepository')->willReturn($repo);

        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->assertEquals($user, $userManager->exists($user));
    }

    public function testUserMailExists()
    {
        $email = 'test@email.com';
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn($email);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->method('findOneBy')->with(['email' => $email])->willReturn($user);
        $this->objectManager->method('getRepository')->willReturn($repo);

        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->assertEquals($user, $userManager->mailExists($user));
    }

    public function testSaveUser()
    {
        $user = $this->createMock(User::class);

        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->assertEquals($userManager, $userManager->save($user));
    }


    public function testChangePassword() {

        $user = $this->createMock(User::class);
        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->assertEquals($userManager, $userManager->changePassword($user));
    }

    public function testGetUserByEmail()
    {
        $email = 'test@test.com';
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn($email);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->method('findOneBy')->with(['email' => $email])->willReturn($user);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->assertEquals($user, $userManager->getUserByEmail($email));
    }

    public function testCannotGetUserByEmailEmpty()
    {
        $email = null;
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn($email);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->method('findOneBy')->with(['email' => $email])->willReturn($user);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->expectException(\Exception::class);
        $userManager->getUserByEmail($email);
    }

    public function testCannotFindUserByEmail()
    {
        $email = 'test@test.com';
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn($email);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->method('findOneBy')->with(['email' => $email])->willReturn(null);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->expectException(\Exception::class);
        $userManager->getUserByEmail($email);
    }

    public function testCanInsertUser()
    {
        $pseudo = 'pseudo';
        $email = 'test@test.com';
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn($email);
        $user->method('getUsername')->willReturn($pseudo);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->expects($this->any())->method('findOneBy')->willReturn(null);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->assertEquals($userManager, $userManager->insert($user));
    }

    public function testCannotInsertUserExists()
    {
        $pseudo = 'pseudo';
        $user = $this->createMock(User::class);
        $user->method('getUsername')->willReturn($pseudo);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->expects($this->any())->method('findOneBy')->willReturn($user);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->expectException(\Exception::class);
        $userManager->insert($user);
    }

    public function testDeleteUser()
    {
        $user_id = 1;
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn($user_id);

        $repo = $this->createMock(ObjectRepository::class);
        $repo->expects($this->any())->method('find')->with($user_id)->willReturn($user);
        $this->objectManager->method('getRepository')->willReturn($repo);

        $userManager = new UserManager($this->objectManager, $this->encoder, $this->dispatcher);
        $this->assertEquals($userManager, $userManager->delete($user_id));
    }

}
