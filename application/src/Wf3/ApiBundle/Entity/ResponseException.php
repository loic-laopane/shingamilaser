<?php

namespace Wf3\ApiBundle\Entity;

use Wf3\ApiBundle\Model\AbstractResponse;

/**
 * ResponseException
 *
 */
class ResponseException extends AbstractResponse
{
    /**
     * @var int
     *
     */
    private $id;


    /**
     * ResponseRequest constructor.
     */
    public function __construct()
    {
        //$this->setStatusCode(parent::STATUS_BAD_REQUEST);
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
}
