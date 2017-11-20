<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Card
 *
 * @ORM\Table(name="card")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardRepository")
 */
class Card
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=6, nullable=true)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="checksum", type="integer")
     */
    private $checksum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activated_at", type="datetime", nullable=true)
     */
    private $activatedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var Center
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Center")
     * @ORM\JoinColumn(nullable=false)
     */
    private $center;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="cards")
     * @ORM\JoinColumn(nullable=true)
     */
    private $customer;

    /**
     * @var string
     * @ORM\Column(name="numero", type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Regex("/\d{10}/", message="This value must have 10 digits")
     */
    private $numero;


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
     * Set code
     *
     * @param string $code
     *
     * @return Card
     */
    public function setCode($code)
    {
        $this->code = $code;
        $this->hydrateNumero();

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set checksum
     *
     * @param integer $checksum
     *
     * @return Card
     */
    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
        $this->hydrateNumero();

        return $this;
    }

    /**
     * Get checksum
     *
     * @return int
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * Set activatedAt
     *
     * @param \DateTime $activatedAt
     *
     * @return Card
     */
    public function setActivatedAt($activatedAt)
    {
        $this->activatedAt = $activatedAt;

        return $this;
    }

    /**
     * Get activatedAt
     *
     * @return \DateTime
     */
    public function getActivatedAt()
    {
        return $this->activatedAt;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Card
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set center
     *
     * @param \AppBundle\Entity\Center $center
     *
     * @return Card
     */
    public function setCenter(\AppBundle\Entity\Center $center = null)
    {
        $this->center = $center;
        $this->hydrateNumero();

        return $this;
    }

    /**
     * Get center
     *
     * @return \AppBundle\Entity\Center
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Card
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
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * @return Customer
     */
    public function hasOwner()
    {
        return $this->getCustomer();
    }

    /**
     * @return string
     */
    private function generateNumero()
    {
        return $this->getCenter()->getCode().$this->getCode().$this->getChecksum();
    }

    /**
     * Set Numero with generated numero
     * @return $this
     */
    private function hydrateNumero()
    {
        $this->setNumero($this->generateNumero());
        return $this;
    }
}
