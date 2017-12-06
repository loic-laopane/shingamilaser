<?php

namespace Wf3\ApiBundle\Model;

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
     * @Serializer\Expose()
     */
    protected $statusCode;

    /**
     * @var
     * @Serializer\Expose()
     */
    protected $message;

    /**
     * Set statusCode
     *
     * @param string $statusCode

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
     * @return AbstractResponse
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
