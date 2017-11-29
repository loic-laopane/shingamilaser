<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use AppBundle\Manager\GameManager;
use AppBundle\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GameManagerTest
{
    private $objectManager;
    private $encoder;
    private $session;
    private $entity;

    public function __construct()
    {
        parent::__construct();

        $this->entity = $this->createMock(Game::class);

        $this->objectManager = $this->createMock(ObjectManager::class);

        $this->encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)->getMock();
        $nativeSessionStorage = $this->createMock(NativeSessionStorage::class);
        $this->session = $this->createMock(Session::class);
        //$this->session->expects($this->once())->method('getFlashBag')->willReturn($nativeSessionStorage);
    }

    public function testCanGetListGames()
    {
        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())->method('findAll')->willReturn($this->getData());
        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);

        $gameManager = new GameManager($this->objectManager, $this->session);
        $this->assertEquals($this->getData(), $gameManager->getList());
    }

    public function testCanInsertGame()
    {
        $this->objectManager->expects($this->once())->method('persist')->with($this->entity);
        $this->objectManager->expects($this->once())->method('flush');

        $nativeSessionStorage = $this->getMockBuilder(NativeSessionStorage::class)
                                    ->setMethods(['add'])
                                    ->getMock();

        $this->session->expects($this->once())->method('getFlashBag')->willReturn($nativeSessionStorage);

        $gameManager = new GameManager($this->objectManager, $this->session);
        $gameManager->insert($this->entity);
    }

    public function testCanDeletetGame()
    {
        $id = 1;
        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())->method('find')->with($id)->willReturn($this->entity);
        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);

        $this->objectManager->expects($this->once())->method('remove')->with($this->entity);
        $this->objectManager->expects($this->once())->method('flush');

        $nativeSessionStorage = $this->getMockBuilder(NativeSessionStorage::class)
            ->setMethods(['add'])
            ->getMock();

        $this->session->expects($this->once())->method('getFlashBag')->willReturn($nativeSessionStorage);

        $gameManager = new GameManager($this->objectManager, $this->session);
        $this->assertTrue($gameManager->delete($id));
    }

    public function testCannotDeletetGame()
    {
        $id = 1;
        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())->method('find')->with($id)->willReturn(null);
        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);

        $nativeSessionStorage = $this->getMockBuilder(NativeSessionStorage::class)
            ->setMethods(['add'])
            ->getMock();

        $this->session->expects($this->once())->method('getFlashBag')->willReturn($nativeSessionStorage);

        $gameManager = new GameManager($this->objectManager, $this->session);
        $this->assertFalse($gameManager->delete($id));
    }

    public function getData()
    {
        return [$this->entity, $this->entity];
    }

}
