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
use AppBundle\Manager\PlayerManager;
use AppBundle\Repository\PlayerRepository;
use Doctrine\Common\Persistence\ObjectManager;
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
}
