<?php

namespace Wf3\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * AbstractResponse
 *
 * @Serializer\ExclusionPolicy("ALL")
 */
abstract class AbstractResponse
{
    const STATUS_OK = 200;
    const STATUS_BAD_REQUEST = 400;

    /**
     * @var
     */
    private $statusCode;

    /**
     * @var
     */
    private $message;
    /**
     * Set statusCode
     *
     * @param string $statusCode

     */
    abstract public function setStatusCode($statusCode);

    /**
     * Get statusCode
     *
     * @return string
     */
    abstract public function getStatusCode();

    /**
     * Set message
     *
     * @param string $message
     *
     * @return ResponseRequest
     */
    abstract public function setMessage($message);

    /**
     * Get message
     *
     * @return string
     */
    abstract public function getMessage();
}
