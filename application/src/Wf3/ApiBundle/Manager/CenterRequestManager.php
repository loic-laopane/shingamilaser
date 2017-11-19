<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 18/11/2017
 * Time: 23:07
 */

namespace Wf3\ApiBundle\Manager;


use Doctrine\Common\Persistence\ObjectManager;
use Wf3\ApiBundle\Entity\Center;
use Wf3\ApiBundle\Entity\CenterRequest;
use Wf3\ApiBundle\Entity\ResponseRequest;

class CenterRequestManager
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ResponseRequest
     */
    private $responseRequest;
    /**
     * @var CardManager
     */
    private $cardManager;

    private $messages;

    public function __construct(ObjectManager $objectManager, CardManager $cardManager)
    {

        $this->objectManager = $objectManager;
        $this->cardManager = $cardManager;
        $this->messages = array();
    }

    public function checkData(array $data, ResponseRequest $responseRequest)
    {
        $this->responseRequest = $responseRequest;
        $this->validRequest($data);
        return $this->responseRequest;
    }
    public function validRequest(array $data)
    {
        if(!isset($data['center']))
        {
           $this->addMessage('Field Center is required');
        }

        if(!isset($data['quantity']) || (isset($data['quantity']) && !($data['quantity']))) {
            $this->addMessage('Field Quantity is required and must not be null');
        }

        $center = $this->findCenter(@$data['center']);
        if(null === $center)
        {
            $this->addMessage('Center code '.@$data['center'].' unknown');
        }

        if(count($this->messages) > 0) {
            $this->responseRequest->setMessage(implode('. ', $this->messages));
            return false;
        }

        $this->create($center, $data['quantity']);

    }

    public function create(Center $center, $quantity)
    {

        $centerRequest = new CenterRequest();
        $centerRequest->setCenter($center);
        $centerRequest->setQuantity($quantity);

        $this->createCards($centerRequest, $quantity);

        $this->objectManager->persist($center);
        $this->objectManager->flush();
        $this->responseRequest->setCenterRequest($centerRequest);
        $this->responseRequest->setStatusCode(200);
        $this->responseRequest->setRequestId($centerRequest->getId());
        $this->responseRequest->setMessage('All cards created on request '.$centerRequest->getId().' for center '.$centerRequest->getCenter()->getCode());
    }



    private function findCenter($numero)
    {
        return $this->objectManager->getRepository(Center::class)->findOneBy(['code' => $numero]);
    }

    private function createCards(CenterRequest $centerRequest, $quantity)
    {
        for($i=0; $i < $quantity; $i++)
        {
            $this->cardManager->create($centerRequest);
        }
        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    private function addMessage($message)
    {
        array_push($this->messages, $message);
        return $this;
    }
}