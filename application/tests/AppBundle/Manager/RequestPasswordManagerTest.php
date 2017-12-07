<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 16:07
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\RequestPassword;
use AppBundle\Entity\User;
use AppBundle\Manager\RequestPasswordManager;
use AppBundle\Repository\RequestPasswordRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class RequestPasswordManagerTest extends TestCase
{

    private $dispatcher;
    private $objectManager;

    public function setUp()
    {
        parent::setUp();
        
        $this->objectManager = $this->createMock(ObjectManager::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
    }

    public function testCreateUser()
    {
        $user = $this->createMock(User::class);
        $requestPasswordManager = new RequestPasswordManager($this->objectManager,$this->dispatcher);
        $this->assertEquals($requestPasswordManager, $requestPasswordManager->create($user));
    }

    public function testGetUserByToken()
    {
        $datetime = (new \DateTime())->modify('+3 minutes');
        $token = uniqid();

        $user = $this->createMock(User::class);

        $requestPassword = $this->createMock(RequestPassword::class);
        $requestPassword->expects($this->once())->method('getUser')->willReturn($user);
        $requestPassword->expects($this->once())->method('getExpiredAt')->willReturn($datetime);

        $repo = $this->createMock(RequestPasswordRepository::class);
        $repo->expects($this->once())->method('getRequestByToken')->with($token)->willReturn($requestPassword);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())->method('getRepository')->willReturn($repo);

        $requestPasswordManager = new RequestPasswordManager($objectManager, $this->dispatcher);
        $this->assertEquals($user, $requestPasswordManager->getUserByToken($token));
    }

    public function testInvalidToken()
    {
        $token = uniqid();

        $repo = $this->createMock(RequestPasswordRepository::class);
        $repo->expects($this->once())->method('getRequestByToken')->with($token)->willReturn(null);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())->method('getRepository')->willReturn($repo);

        $requestPasswordManager = new RequestPasswordManager($objectManager, $this->dispatcher);
        $this->expectException(\Exception::class);
        $requestPasswordManager->getUserByToken($token);
    }

    public function testTokenExpired()
    {
        $datetime = (new \DateTime())->modify('-3 minutes');
        $token = uniqid();

        $requestPassword = $this->createMock(RequestPassword::class);
        $requestPassword->expects($this->once())->method('getExpiredAt')->willReturn($datetime);

        $repo = $this->createMock(RequestPasswordRepository::class);
        $repo->expects($this->once())->method('getRequestByToken')->with($token)->willReturn($requestPassword);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())->method('getRepository')->willReturn($repo);

        $requestPasswordManager = new RequestPasswordManager($objectManager, $this->dispatcher);
        $this->expectException(\Exception::class);
        $requestPasswordManager->getUserByToken($token);
    }

    public function testUserNotFound()
    {
        $datetime = (new \DateTime())->modify('+3 minutes');
        $token = uniqid();


        $requestPassword = $this->createMock(RequestPassword::class);
        $requestPassword->expects($this->once())->method('getUser')->willReturn(null);
        $requestPassword->expects($this->once())->method('getExpiredAt')->willReturn($datetime);

        $repo = $this->createMock(RequestPasswordRepository::class);
        $repo->expects($this->once())->method('getRequestByToken')->with($token)->willReturn($requestPassword);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())->method('getRepository')->willReturn($repo);

        $requestPasswordManager = new RequestPasswordManager($objectManager, $this->dispatcher);
        $this->expectException(\Exception::class);
        $requestPasswordManager->getUserByToken($token);
    }
}