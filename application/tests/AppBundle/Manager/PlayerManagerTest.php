<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Manager\PlayerManager;
use AppBundle\Repository\CardRepository;
use AppBundle\Repository\PlayerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;


class PlayerManagerTest extends TestCase
{
    private $objectManager;

    public function setUp()
    {
        parent::setUp();

        $this->objectManager = $this->createMock(ObjectManager::class);

    }

    public function testCanInsertPlayer()
    {
        $player = $this->createMock(Player::class);
        $playerManager = new PlayerManager($this->objectManager);
        $this->assertEquals($playerManager, $playerManager->insert($player));
    }

    public function testPlayerExists()
    {
        $customer = $this->createMock(Customer::class);

        $game = $this->createMock(Game::class);

        $player = $this->createMock(Player::class);
        $player->method('getCustomer')->willReturn($customer);
        $player->method('getGame')->willReturn($game);

        $repo = $this->createMock(PlayerRepository::class);
        $repo->method('findOneBy')->with(['customer' => $customer, 'game' => $game])->willReturn($player);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $playerManager = new PlayerManager($this->objectManager);
        $this->assertEquals($player, $playerManager->exists($player));
    }

    public function testAddPlayer()
    {
        $customer = $this->createMock(Customer::class);

        $game = $this->createMock(Game::class);
        $card = $this->createMock(Card::class);

        $player = $this->createMock(Player::class);
        $player->method('getCustomer')->willReturn($customer);
        $player->method('getGame')->willReturn($game);

        $repo = $this->createMock(CardRepository::class);
        $repo->method('findCustomerActiveCard')->with($customer)->willReturn($card);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $playerManager = new PlayerManager($this->objectManager);
        $this->assertEquals($playerManager, $playerManager->add($customer, $game));
    }

    public function testCanRemovePlayer()
    {
        $customer = $this->createMock(Customer::class);

        $game = $this->createMock(Game::class);

        $player = $this->createMock(Player::class);

        $repo = $this->createMock(PlayerRepository::class);
        $repo->method('findOneBy')->with(['customer' => $customer, 'game' => $game])->willReturn($player);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $playerManager = new PlayerManager($this->objectManager);
        $this->assertEquals($playerManager, $playerManager->remove($customer, $game));
    }

    public function testCanGetPlayerList()
    {
        $player = $this->createMock(Player::class);
        $players = [$player, $player];

        $repo = $this->createMock(PlayerRepository::class);
        $repo->method('findAll')->willReturn($players);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $playerManager = new PlayerManager($this->objectManager);
        $this->assertEquals($players, $playerManager->getList());
    }

    public function testCanGetCustomersByGame()
    {
        $customer = $this->createMock(Customer::class);
        $customers = [$customer, $customer];
        $game = $this->createMock(Game::class);

        $repo = $this->createMock(PlayerRepository::class);
        $repo->method('getCustomersByGame')->with($game)->willReturn($customers);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $playerManager = new PlayerManager($this->objectManager);
        $this->assertEquals($customers, $playerManager->getCustomersByGame($game));
    }

    public function testCanGetGamesCustomerWithCard()
    {
        $customer = $this->createMock(Customer::class);

        $player = $this->createMock(Player::class);
        $players = [$player, $player];

        $repo = $this->createMock(PlayerRepository::class);
        $repo->method('getGamesCustomerWithCard')->with($customer)->willReturn($players);

        $this->objectManager->method('getRepository')->willReturn($repo);

        $playerManager = new PlayerManager($this->objectManager);
        $this->assertEquals($players, $playerManager->getGamesCustomerWithCard($customer));
    }

}
