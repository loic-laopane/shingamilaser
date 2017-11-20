<?php

namespace Wf3\ApiBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResponseException
 *
 * @ORM\Table(name="response_request")
 * @ORM\Entity(repositoryClass="Wf3\ApiBundle\Repository\ResponseRequestRepository")
 */
class ResponseException extends AbstractResponse
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
