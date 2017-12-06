<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 19/11/2017
 * Time: 00:06
 */

namespace Wf3\ApiBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Wf3\ApiBundle\Entity\Card;
use Wf3\ApiBundle\Entity\Center;
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
        $rand = mt_rand(0, 999999);
        return $this->formatUniqId($rand);
    }

    /**
     * @param $int
     * @return string
     */
    private function formatUniqId($int)
    {
        $diff =  Card::CODE_LENGTH - strlen($int);
        for ($i = 0; $i < $diff; $i++) {
            $int = '0'.$int;
        }
        return $int;
    }

    /**
     * Liste les cards d'apres un id de centerRequest et le centre
     * @param $center_code
     * @param $request_id
     * @return array|Card[]
     */
    public function requestedCards($center_code, $request_id)
    {
        $centerRequest = $this->objectManager->getRepository('ApiBundle:CenterRequest')->findCenterRequestByCenterAndId($center_code, $request_id);
        if (null === $centerRequest) {
            throw new Exception('Request not found');
        }

        $cards = $this->objectManager->getRepository('ApiBundle:Card')->findBy(array(
            'centerRequest' => $centerRequest
        ));

        return $cards;
    }
}
