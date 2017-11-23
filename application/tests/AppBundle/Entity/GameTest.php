<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 22/11/2017
 * Time: 15:10
 */

namespace test\AppBundle\Entity;

use AppBundle\Entity\Game;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private $game;

    public function __construct()
    {
        parent::__construct();
        $this->game = new Game();
    }

    public function testCanGetId()
    {
        $this->assertNull($this->game->getId());
    }

    public function testCanBeInstanciated()
    {
        $this->assertInstanceOf(Game::class, $this->game);
    }

    public function testCanSetTitle()
    {
        $title = 'Party One';
        $this->assertInstanceOf(Game::class, $this->game->setTitle($title));
        $this->assertEquals($title, $this->game->getTitle());
    }

    public function testCanSetCreatedAt()
    {
        $datetime = new \DateTime();
        $this->assertInstanceOf(Game::class, $this->game->setCreatedAt($datetime));
        $this->assertEquals($datetime, $this->game->getCreatedAt());
    }

    public function testInsertInvalidCreatedAt()
    {
        $datetime = date('d/m/Y');
        $this->expectException(\TypeError::class);
        $this->game->setCreatedAt($datetime);
    }

    public function testCanSetStartedAt()
    {
        $datetime = new \DateTime();
        $this->assertInstanceOf(Game::class, $this->game->setStartedAt($datetime));
        $this->assertEquals($datetime, $this->game->getStartedAt());
    }

    public function testInsertInvalidStarteddAt()
    {
        $datetime = date('d/m/Y');
        $this->expectException(\TypeError::class);
        $this->game->setStartedAt($datetime);
    }

    public function testCanSetEndedAt()
    {
        $datetime = new \DateTime();
        $this->assertInstanceOf(Game::class, $this->game->setEndedAt($datetime));
        $this->assertEquals($datetime, $this->game->getEndedAt());
    }

    public function testInsertInvalidEndedAt()
    {
        $datetime = date('d/m/Y');
        $this->expectException(\TypeError::class);
        $this->game->setEndedAt($datetime);
    }
}