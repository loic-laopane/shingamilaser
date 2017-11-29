<?php

namespace Wf3\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Wf3\ApiBundle\Model\AbstractResponse;

/**
 * ResponseRequest
 *
 * @ORM\Table(name="response_request")
 * @ORM\Entity(repositoryClass="Wf3\ApiBundle\Repository\ResponseRequestRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class ResponseRequest extends AbstractResponse
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="request_id", type="integer", nullable=true)
     * @Serializer\Expose()
     */
    private $requestId;

    /**
     * @var CenterRequest
     * @ORM\ManyToOne(targetEntity="Wf3\ApiBundle\Entity\CenterRequest")
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
     * Set requestId
     *
     * @param integer $requestId
     *
     * @return ResponseRequest
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;

        return $this;
    }

    /**
     * Get requestId
     *
     * @return int
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * Set centerRequest
     *
     * @param \Wf3\ApiBundle\Entity\CenterRequest $centerRequest
     *
     * @return ResponseRequest
     */
    public function setCenterRequest(\Wf3\ApiBundle\Entity\CenterRequest $centerRequest = null)
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
}
