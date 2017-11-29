<?php

namespace Wf3\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * CenterRequest
 *
 * @ORM\Table(name="center_request")
 * @ORM\Entity(repositoryClass="Wf3\ApiBundle\Repository\CenterRequestRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class CenterRequest
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
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="answered_at", type="datetime", nullable=true)
     */
    private $answeredAt;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=true)
     */
    private $status;

    /**
     * @var Center
     * @ORM\ManyToOne(targetEntity="Wf3\ApiBundle\Entity\Center")
     */
    private $center;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Wf3\ApiBundle\Entity\Card", mappedBy="centerRequest")
     * @Serializer\Expose()
     */
    private $cards;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return CenterRequest
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CenterRequest
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
     * Set answeredAt
     *
     * @param \DateTime $answeredAt
     *
     * @return CenterRequest
     */
    public function setAnsweredAt($answeredAt)
    {
        $this->answeredAt = $answeredAt;

        return $this;
    }

    /**
     * Get answeredAt
     *
     * @return \DateTime
     */
    public function getAnsweredAt()
    {
        return $this->answeredAt;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return CenterRequest
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set center
     *
     * @param Center $center
     *
     * @return CenterRequest
     */
    public function setCenter(\Wf3\ApiBundle\Entity\Center $center = null)
    {
        $this->center = $center;

        return $this;
    }

    /**
     * Get center
     *
     * @return Center
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * Add card
     *
     * @param \Wf3\ApiBundle\Entity\Card $card
     *
     * @return CenterRequest
     */
    public function addCard(\Wf3\ApiBundle\Entity\Card $card)
    {
        $this->cards[] = $card;

        return $this;
    }

    /**
     * Remove card
     *
     * @param \Wf3\ApiBundle\Entity\Card $card
     */
    public function removeCard(\Wf3\ApiBundle\Entity\Card $card)
    {
        $this->cards->removeElement($card);
    }

    /**
     * Get cards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCards()
    {
        return $this->cards;
    }
}
