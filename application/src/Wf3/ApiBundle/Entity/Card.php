<?php

namespace Wf3\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Card
 *
 * @ORM\Table(name="card")
 * @ORM\Entity(repositoryClass="Wf3\ApiBundle\Repository\CardRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Serializer\ExclusionPolicy("ALL")
 */
class Card
{

    const CODE_LENGTH = 6;
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
     * @ORM\Column(name="numero", type="string", length=10, nullable=true)
     * @Serializer\Expose()
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=6)
     * @Serializer\Expose()
     */
    private $code;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var CenterRequest
     * @ORM\ManyToOne(targetEntity="Wf3\ApiBundle\Entity\CenterRequest", cascade={"persist"}, inversedBy="cards")
     */
    private $centerRequest;

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
     * Set code
     *
     * @param string $code
     *
     * @return Card
     */
    public function setCode($code)
    {
        $this->code = $code;

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
     * Set centerRequest
     *
     * @param \Wf3\ApiBundle\Entity\CenterRequest $centerRequest
     *
     * @return Card
     */
    public function setCenterRequest(CenterRequest $centerRequest = null)
    {
        $this->centerRequest = $centerRequest;

        return $this;
    }

    /**
     * Get centerRequest
     *
     * @return \Wf3\ApiBundle\Entity\CenterRequest
     */
    public function getCenterRequest()
    {
        return $this->centerRequest;
    }

    /**
     * @ORM\PrePersist()
     */
    public function generateNumero()
    {
        $baseNumero = $this->getCenterRequest()->getCenter()->getCode().$this->getCode();
        $splitNumero = str_split($baseNumero);
        $checksum = array_sum($splitNumero) % 9;
        $this->setNumero($baseNumero.$checksum);
    }
}
