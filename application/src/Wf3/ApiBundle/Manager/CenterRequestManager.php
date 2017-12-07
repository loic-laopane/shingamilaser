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
use Wf3\ApiBundle\Model\AbstractResponse;
use Wf3\ApiBundle\Model\ResponseRequest;

class CenterRequestManager
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var AbstractResponse
     */
    private $abstractResponse;
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
    private $request_accept = array('center', 'quantity');
    private $get_all_accept = array('center', 'request_id');

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
     * Retourne une ResponseRequete avec validation des data
     * @param array $data
     * @param ResponseRequest $responseRequest
     * @return AbstractResponse
     */
    public function request(array $data, AbstractResponse $responseRequest)
    {
        $this->abstractResponse = $responseRequest;
        $this->validRequest($data);
        return $this->abstractResponse;
    }

    /**
     * @param array $data
     * @param AbstractResponse $responseRequest
     * @return AbstractResponse
     */
    public function getAll(array $data, AbstractResponse $responseRequest)
    {
        $this->abstractResponse = $responseRequest;
        $this->validGetAll($data);
        return $this->abstractResponse;
    }

    /**
     * Verification que les champs entrés sont bien valide selon la method de l'api appelée
     * @param array $data
     * @param $accept
     */
    public function checkEntries(array $data, $accept)
    {
        $missing_fields = array_diff($accept, array_keys($data));
        $unknown_fields = array_diff(array_keys($data), $accept);
        foreach ($missing_fields as $field) {
            throw new Exception('Field ['.$field.'] is required');
        }
        foreach ($unknown_fields as $field) {
            throw new Exception('Field ['.$field.'] is unknown');
        }
        return $this;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validRequest(array $data)
    {
        $this->checkEntries($data, $this->request_accept);

        if (!($data['quantity'])) {
            throw new Exception('Field [quantity] is required and must not be null');
        }

        $center = $this->findCenter($data['center']);

        $this->create($center, $data['quantity']);

    }

    /**
     * @param array $data
     */
    public function validGetAll(array $data)
    {
        $this->checkEntries($data, $this->get_all_accept);

        $cards = $this->cardManager->requestedCards($data['center'], $data['request_id']);

        if (null === $cards) {
            throw new Exception('No card found');
        }

        $this->abstractResponse->setStatusCode(200)
                                    ->setMessage(count($cards).' card(s) found')
                                    ->setCards($cards);
    }

    /**
     * Creer un liste de carte d'apres un center et un quantite
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

        $this->abstractResponse->setCenterRequest($centerRequest)
                              ->setStatusCode(200)
                              ->setRequestId($centerRequest->getId())
                              ->setMessage($quantity.' card(s) created on request #'.$centerRequest->getId().' for center '.$centerRequest->getCenter()->getCode());
    }

    /**
     * @param $numero
     * @return object
     */
    private function findCenter($numero)
    {
        $center = $this->objectManager->getRepository(Center::class)->findOneBy(['code' => $numero]);
        if (false === $center instanceof Center) {
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
        for ($i=0; $i < $quantity; $i++) {
            $this->cardManager->create($centerRequest);
        }
        return $this;
    }
}
