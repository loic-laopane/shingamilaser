<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started_at", type="datetime")
     */
    private $startedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ended_at", type="datetime")
     */
    private $endedAt;

    /**
     * @var CustomerGame
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CustomerGame", mappedBy="game")
     */
    private $customerGame;


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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Game
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set startedAt
     *
     * @param \DateTime $startedAt
     *
     * @return Game
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get startedAt
     *
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set endedAt
     *
     * @param \DateTime $endedAt
     *
     * @return Game
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    /**
     * Get endedAt
     *
     * @return \DateTime
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customerGame = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add customerGame
     *
     * @param \AppBundle\Entity\CustomerGame $customerGame
     *
     * @return Game
     */
    public function addCustomerGame(\AppBundle\Entity\CustomerGame $customerGame)
    {
        $this->customerGame[] = $customerGame;

        return $this;
    }

    /**
     * Remove customerGame
     *
     * @param \AppBundle\Entity\CustomerGame $customerGame
     */
    public function removeCustomerGame(\AppBundle\Entity\CustomerGame $customerGame)
    {
        $this->customerGame->removeElement($customerGame);
    }

    /**
     * Get customerGame
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerGame()
    {
        return $this->customerGame;
    }
}
