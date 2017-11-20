<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 19/11/2017
 * Time: 00:06
 */

namespace Wf3\ApiBundle\Manager;


use Doctrine\Common\Persistence\ObjectManager;
use Wf3\ApiBundle\Entity\Card;
use Wf3\ApiBundle\Entity\CenterRequest;

class CardManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * CardManager constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param CenterRequest $centerRequest
     * @return $this
     */
    public function create(CenterRequest $centerRequest)
    {
        $card = new Card();
        $card->setCode($this->createUniqId());
        $card->setCenterRequest($centerRequest);
        $this->objectManager->persist($card);
        $this->objectManager->flush();

        return $this;
    }

    /**
     * @return int
     */
    private function createUniqId()
    {
        return mt_rand(100000, 999999);
    }


}