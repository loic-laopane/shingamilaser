<?php

namespace Wf3\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResponseRequest
 *
 * @ORM\Table(name="response_request")
 * @ORM\Entity(repositoryClass="Wf3\ApiBundle\Repository\ResponseRequestRepository")
 */
class ResponseRequest
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
     * @ORM\Column(name="status_code", type="string", length=3)
     */
    private $statusCode;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @var int
     *
     * @ORM\Column(name="request_id", type="integer", nullable=true)
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
     * Set statusCode
     *
     * @param string $statusCode
     *
     * @return ResponseRequest
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get statusCode
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return ResponseRequest
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
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
