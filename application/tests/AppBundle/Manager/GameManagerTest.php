<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Entity\User;
use AppBundle\Event\OfferEvent;
use AppBundle\Manager\CustomerManager;
use AppBundle\Manager\GameManager;
use AppBundle\Manager\UserManager;
use AppBundle\Repository\PlayerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class GameManagerTest extends TestCase
{
    private $objectManager;
    private $encoder;
    private $template;
    private $customerManager;
    private $translator;
    private $dispatcher;

    public function setUp()
    {
        parent::setUp();

        $this->objectManager = $this->createMock(ObjectManager::class);

        $this->encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)->getMock();
        $this->template = $this->createMock(EngineInterface::class);
        $this->customerManager = $this->createMock(CustomerManager::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);

        //$this->session->expects($this->once())->method('getFlashBag')->willReturn($nativeSessionStorage);
    }

//    public function testCanGetListGames()
//    {
//        $repository = $this->createMock(ObjectRepository::class);
//        $repository->expects($this->once())->method('findAll')->willReturn($this->getData());
//        $this->objectManager->expects($this->once())->method('getRepository')->willReturn($repository);
//
//        $gameManager = new GameManager($this->objectManager, $this->session);
//        $this->assertEquals($this->getData(), $gameManager->getList());
//    }

    public function testCanInsertGame()
    {
        $game = $this->createMock(Game::class);
        $gameManager = new GameManager($this->objectManager, $this->template, $this->customerManager, $this->dispatcher,$this->translator);
        $this->assertEquals($gameManager, $gameManager->insert($game));
    }

    public function testCanSaveGame()
    {
        $game = $this->createMock(Game::class);
        $gameManager = new GameManager($this->objectManager, $this->template, $this->customerManager, $this->dispatcher,$this->translator);
        $this->assertEquals($gameManager, $gameManager->save($game));
    }


    public function testCanRecordStartGame()
    {
        $game = $this->createMock(Game::class);
        $game->method('getStartedAt')->willReturn(null);
        $game->method('getPlayers')->willReturn(['player 1', 'player 2']);
        $gameManager = new GameManager($this->objectManager, $this->template, $this->customerManager, $this->dispatcher,$this->translator);
        $this->assertEquals($gameManager, $gameManager->record($game));
    }

    public function testCannotRecordStartGame()
    {
        $game = $this->createMock(Game::class);
        $game->method('getStartedAt')->willReturn(null);
        $game->method('getPlayers')->willReturn([]);
        $gameManager = new GameManager($this->objectManager, $this->template, $this->customerManager, $this->dispatcher,$this->translator);
        $this->expectException(\Exception::class);
        $gameManager->record($game);
    }

    public function testCanRecordStopGame()
    {
        $customer = $this->createMock(Customer::class);
        $player = $this->createMock(Player::class);
        $player->method('getCustomer')->willReturn($customer);
        $players = [$player, $player];
        $game = $this->createMock(Game::class);
        $game->method('getStartedAt')->willReturn('datetime');
        $game->method('setEndedAt')->willReturn($game);
        $game->method('getPlayers')->willReturn($players);

        $repo = $this->createMock(PlayerRepository::class);
        $repo->method('findBy')->with(['game' => $game])->willReturn($players);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $gameManager = new GameManager($this->objectManager, $this->template, $this->customerManager, $this->dispatcher,$this->translator);
        $this->assertEquals($gameManager, $gameManager->record($game));
    }

    public function testCanListGame()
    {
        $game = $this->createMock(Game::class);
        $games = [$game, $game];
        $repo = $this->createMock(PlayerRepository::class);
        $repo->method('findAll')->willReturn($games);
        $this->objectManager->method('getRepository')->willReturn($repo);

        $gameManager = new GameManager($this->objectManager, $this->template, $this->customerManager, $this->dispatcher,$this->translator);
        $this->assertEquals($games, $gameManager->getList());
    }

    public function testSearchCustomerWithGame()
    {
        $game = $this->createMock(Game::class);
        $data = [];
        $response = array(
            'status' => 0,
            'message' => null,
            'data' => null
        );
        $gameManager = new GameManager($this->objectManager, $this->template, $this->customerManager, $this->dispatcher,$this->translator);
        $this->assertEquals($response, $gameManager->searchCustomerWithGame($data, $game));
    }
}
