<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 18/11/2017
 * Time: 23:07
 */

namespace Wf3\ApiBundle\Manager;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;
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

    /**
     * @var array
     */
    private $messages;

    /**
     * @var array
     */
    private $accept = array('center', 'quantity');

    /**
     * CenterRequestManager constructor.
     * @param ObjectManager $objectManager
     * @param CardManager $cardManager
     */
    public function __construct(ObjectManager $objectManager, CardManager $cardManager)
    {

        $this->objectManager = $objectManager;
        $this->cardManager = $cardManager;
        $this->messages = array();
    }

    /**
     * @param array $data
     * @param ResponseRequest $responseRequest
     * @return ResponseRequest
     */
    public function checkData(array $data, ResponseRequest $responseRequest)
    {
        $this->responseRequest = $responseRequest;
        $this->validRequest($data);
        return $this->responseRequest;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validRequest(array $data)
    {
        $missing_fields = array_diff($this->accept, array_keys($data));
        $unknown_fields = array_diff(array_keys($data), $this->accept);
        foreach($missing_fields as $field)
        {
            throw new Exception('Field '.$field.' is required');
        }
        foreach($unknown_fields as $field)
        {
            throw new Exception('Field '.$field.' is unknown');
        }

        if(!($data['quantity'])) {
            throw new Exception('Field Quantity is required and must not be null');
        }

        $center = $this->findCenter($data['center']);

        $this->create($center, $data['quantity']);

    }

    /**
     * @param Center $center
     * @param $quantity
     */
    public function create(Center $center, $quantity)
    {
        $centerRequest = new CenterRequest();
        $centerRequest->setCenter($center);
        $centerRequest->setQuantity($quantity);

        $this->createCards($centerRequest, $quantity);

        $this->objectManager->persist($center);
        $this->objectManager->flush();

        $this->responseRequest->setCenterRequest($centerRequest)
                              ->setStatusCode(200)
                              ->setRequestId($centerRequest->getId())
                              ->setMessage('All cards created on request #'.$centerRequest->getId().' for center '.$centerRequest->getCenter()->getCode());
    }

    /**
     * @param $numero
     * @return object
     */
    private function findCenter($numero)
    {
        $center = $this->objectManager->getRepository(Center::class)->findOneBy(['code' => $numero]);
        if(false === $center instanceof Center) {
            throw new Exception('Center code '.$numero.' unknown');
        }
        return $center;
    }

    /**
     * @param CenterRequest $centerRequest
     * @param $quantity
     * @return $this
     */
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