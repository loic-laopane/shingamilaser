<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 *
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="customerGames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @var Game
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Game", inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @var Card
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Card")
     * @ORM\JoinColumn(nullable=true)
     */
    private $card;

    /**
     * CustomerGame constructor.
     */
    public function __construct()
    {
        $this->score = 0;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return Player
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Player
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set game
     *
     * @param \AppBundle\Entity\Game $game
     *
     * @return Player
     */
    public function setGame(\AppBundle\Entity\Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \AppBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return Player
     */
    public function setCard(\AppBundle\Entity\Card $card = null)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return \AppBundle\Entity\Card
     */
    public function getCard()
    {
        return $this->card;
    }
}
