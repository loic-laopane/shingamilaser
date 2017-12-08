<?php

namespace Wf3\ApiBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ResponseGetAll
 *
 * @Serializer\ExclusionPolicy("ALL")
 */
class ResponseGetAll extends AbstractResponse
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
     * @var array
     *
     * @Serializer\Expose()
     */
    private $cards;


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
     * @return array
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @param array $cards
     * @return ResponseGetAll
     */
    public function setCards($cards)
    {
        $this->cards = $cards;
        return $this;
    }
}
