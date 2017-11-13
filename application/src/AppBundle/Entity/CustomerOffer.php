<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerOffer
 *
 * @ORM\Table(name="customer_offer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerOfferRepository")
 */
class CustomerOffer
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
     * @ORM\Column(name="used_at", type="datetime", nullable=true)
     */
    private $usedAt;


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
     * Set usedAt
     *
     * @param \DateTime $usedAt
     *
     * @return CustomerOffer
     */
    public function setUsedAt($usedAt)
    {
        $this->usedAt = $usedAt;

        return $this;
    }

    /**
     * Get usedAt
     *
     * @return \DateTime
     */
    public function getUsedAt()
    {
        return $this->usedAt;
    }
}

