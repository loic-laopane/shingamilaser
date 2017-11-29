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
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="customerOffers")
     */
    private $customer;

    /**
     * @var Offer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Offer")
     */
    private $offer;


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

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return CustomerOffer
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
     * Set offer
     *
     * @param \Appbundle\Entity\Offer $offer
     *
     * @return CustomerOffer
     */
    public function setOffer(\Appbundle\Entity\Offer $offer = null)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return \Appbundle\Entity\Offer
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CustomerOffer
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
}
