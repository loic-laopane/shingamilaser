<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Card
 *
 * @ORM\Table(name="card")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardRepository")
 *
 * @Serializer\ExclusionPolicy("ALL")
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
     * @ORM\Column(name="code", type="string", length=6)
     *
     * @Serializer\Expose
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="checksum", type="integer")
     *
     * @Serializer\Expose
     */
    private $checksum;

    /**
     * @var
     * @ORM\Column(type="string", length=10)
     * @Serializer\Expose
     */
    private $numero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var Center
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Center")
     *
     * @Serializer\Expose
     */
    private $center;

    /**
     * @var CenterRequest
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CenterRequest")
     */
    private $centerRequest;


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

        $this->generateChecksum();

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Card
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
     * Set center
     *
     * @param \AppBundle\Entity\Center $center
     *
     * @return Card
     */
    public function setCenter(\AppBundle\Entity\Center $center = null)
    {
        $this->center = $center;

        $this->generateChecksum();

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
     * Set centerRequest
     *
     * @param \AppBundle\Entity\CenterRequest $centerRequest
     *
     * @return Card
     */
    public function setCenterRequest(\AppBundle\Entity\CenterRequest $centerRequest = null)
    {
        $this->centerRequest = $centerRequest;

        return $this;
    }

    /**
     * Get centerRequest
     *
     * @return \AppBundle\Entity\CenterRequest
     */
    public function getCenterRequest()
    {
        return $this->centerRequest;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Card
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @return string
     */
    private function generateNumero()
    {
        return $this->getCenter()->getCode().$this->getCode().$this->getChecksum();
    }

    /**
     * Update le
     * @return $this
     */
    public function updateNumero()
    {

        $this->setNumero($this->generateNumero());

        return $this;
    }

    /**
     * Genere le checksum
     * sommes des chiffres du code_center et code_card
     * @return $this
     */
    private function generateChecksum()
    {
        if(null!== $this->getCenter() && null !== $this->getCode())
        {
            $str = $this->getCenter()->getCode().$this->getCode();
            $split = str_split($str);
            $checksum = array_sum($split)%9;
            $this->setChecksum($checksum);
            $this->updateNumero();

        }
        return $this;
    }
}
