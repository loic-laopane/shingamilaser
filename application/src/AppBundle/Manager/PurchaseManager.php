<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/11/2017
 * Time: 10:42
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Card;
use AppBundle\Entity\Purchase;
use AppBundle\Event\PurchaseEvent;
use Doctrine\Common\Persistence\ObjectManager;

use GuzzleHttp\Client;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Workflow\Workflow;

class PurchaseManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var Serializer
     */
    private $serializer;

    private $base_uri;
    /**
     * @var Workflow
     */
    private $workflow;


    public function __construct(Workflow $workflow,
                                $base_uri,
                                ObjectManager $objectManager,
                                SessionInterface $session,
                                EventDispatcherInterface $dispatcher,
                                SerializerInterface $serializer
                                )
    {
        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository(Purchase::class);
        $this->session = $session;
        $this->dispatcher = $dispatcher;
        $this->serializer = $serializer;
        $this->workflow = $workflow;
        $this->base_uri = $base_uri;
    }

    public function save(Purchase $purchase)
    {
        $this->objectManager->persist($purchase);
        $this->objectManager->flush();
        $this->dispatcher->dispatch(PurchaseEvent::EVENT, new PurchaseEvent($purchase));
        return $this;
    }
    /**
     * @param Purchase $purchase
     * @return $this
     */
    public function create(Purchase $purchase)
    {
        $this->save($purchase);
        return $this;
    }

    /**
     * @param $numero
     * @return Purchase|null|object
     */
    public function search($numero)
    {
       return $this->repository->findOneBy(['numero' => $numero]);
    }

    /**
     * @param Purchase $purchase
     */
    private function checkPrerequisite(Purchase $purchase)
    {
        $user = $purchase->getRequester();
        if(null === $user) {
            $this->setErrorStatus($purchase);
            throw new Exception('alert.no_request_found_on_purchase');//No requester found on this purchase
        }
        if(null === $user->getCenter()) {
            $this->setErrorStatus($purchase);
            throw new Exception('alert.no_center_link_to_current_user'); //No center associated on current user
        }
        if(!$purchase->getQuantity()) {
            $this->setErrorStatus($purchase);
            throw new Exception('alert.quantity_not_null_to_send_request'); //
        }
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function makeRequest(Purchase $purchase)
    {
        $this->checkPrerequisite($purchase);

        //Si on est en erreur, on revalide
        if($this->workflow->can($purchase, 'validate'))
        {
            $this->workflow->apply($purchase, 'validate');
        }

        if($this->workflow->can($purchase, 'revalidate'))
        {
            $this->workflow->apply($purchase, 'revalidate');
        }

        $client = new Client(['base_uri' => $this->base_uri]);
        $data = array(
            'center' => $purchase->getRequester()->getCenter()->getCode(),
            'quantity' => $purchase->getQuantity()
        );
        $jsonData = $this->serializer->serialize($data, 'json');
        $response = $client->request('POST', 'request', array(
            'body' => $jsonData
        ));

        $this->threadRequest($purchase, $response);

        return $this;
    }

    /**
     * @param Purchase $purchase
     * @param ResponseInterface $response
     * @return $this
     */
    private function threadRequest(Purchase $purchase, ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();
        $data = $this->serializer->deserialize($content, 'array', 'json');
        $return = array();
        $return['status'] = $data['status_code'];
        $return['message'] = $data['message'];
        if ($data['status_code'] === 400)
        {
            $this->setErrorStatus($purchase);
            throw new Exception($data['message']);
        }

        if(isset($data['request_id'])) {

            $this->setRequestedStatus($purchase, $data['request_id']);
            $this->session->getFlashBag()->add('success', $data['message']);
        }

        return $this;

    }

    /**
     * @param Purchase $purchase
     * @return $this
     */
    public function getCards(Purchase $purchase)
    {
        $this->checkPrerequisite($purchase);

        $client = new Client(['base_uri' => $this->base_uri]);
        $data = array(
            'center' => $purchase->getRequester()->getCenter()->getCode(),
            'request_id' => $purchase->getReference()
        );
        $jsonData = $this->serializer->serialize($data, 'json');
        $response = $client->request('POST', 'cards', array(
            'body' => $jsonData
        ));

        $this->threadGetCards($purchase, $response);

        return $this;
    }

    /**
     * @param Purchase $purchase
     * @param ResponseInterface $response
     * @return $this
     */
    private function threadGetCards(Purchase $purchase, ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();
        $data = $this->serializer->deserialize($content, 'array', 'json');
        $return = array();
        $return['status'] = $data['status_code'];
        $return['message'] = $data['message'];
        if ($data['status_code'] === 400)
        {
            $this->setErrorStatus($purchase);
            throw new Exception($data['message']);
        }

        if(isset($data['cards'])) {
            foreach($data['cards'] as $api_card)
            {
                $card = new Card();
                $card->setCode($api_card['code']);
                $card->setNumero($api_card['numero']);
                $card->setCenter($purchase->getRequester()->getCenter());
                $card->setPurchase($purchase);

                $this->objectManager->persist($card);
            }
            $this->objectManager->flush();

            $this->setCompletedStatus($purchase);

            $this->session->getFlashBag()->add('success', $data['message']);
        }

        return $this;
    }

    /**
     * @param Purchase $purchase
     * @param $reference
     */
    private function setRequestedStatus(Purchase $purchase, $reference)
    {
        if (!$this->workflow->can($purchase, 'request')) {
            throw new Exception('Purchase cannot be requested');
        }
        $this->workflow->apply($purchase, 'request');
        $purchase->setReference($reference);
        $purchase->setUpdatedAt(new \DateTime());
        $this->save($purchase);
    }

    /**
     * @param Purchase $purchase
     * @return $this
     */
    private function setErrorStatus(Purchase $purchase)
    {
        if($this->workflow->can($purchase, 'error'))
        {
            $this->workflow->apply($purchase, 'error');
            $purchase->setUpdatedAt(new \DateTime());
            $this->save($purchase);
        }
        return $this;

    }

    /**
     * @param Purchase $purchase
     * @return $this
     */
    private function setCompletedStatus(Purchase $purchase)
    {
        if(!$this->workflow->can($purchase, 'complete'))
        {
            throw new Exception('Purchase cannot be as Completed status');
        }
        $this->workflow->apply($purchase, 'complete');
        $purchase->setUpdatedAt(new \DateTime());
        $this->save($purchase);
        return $this;
    }



}
