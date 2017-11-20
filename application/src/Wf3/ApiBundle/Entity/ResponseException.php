<?php

namespace Wf3\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ResponseException
 *
 * @ORM\Table(name="response_request")
 * @ORM\Entity(repositoryClass="Wf3\ApiBundle\Repository\ResponseRequestRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class ResponseException extends AbstractResponse
{
    const STATUS_OK = 200;
    const STATUS_BAD_REQUEST = 400;
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
     * @var string
     *
     * @ORM\Column(name="status_code", type="string", length=3)
     * @Serializer\Expose()
     */
    private $statusCode;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     * @Serializer\Expose()
     */
    private $message;


    /**
     * ResponseRequest constructor.
     */
    public function __construct()
    {
        $this->setStatusCode(parent::STATUS_BAD_REQUEST);
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
     * Set statusCode
     *
     * @param string $statusCode
     *
     * @return ResponseException
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
     * @return ResponseException
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

}
