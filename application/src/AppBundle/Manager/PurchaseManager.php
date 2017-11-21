<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/11/2017
 * Time: 10:42
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Purchase;
use AppBundle\Entity\User;
use AppBundle\Event\PurchaseEvent;
use Doctrine\Common\Persistence\ObjectManager;

use GuzzleHttp\Client;
use JMS\Serializer\Serializer;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Workflow\Transition;
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
    /**
     * @var Client
     */
    private $client;

    private $base_uri = 'http://localhost/web/app_dev.php/api/';
    /**
     * @var Workflow
     */
    private $workflow;

    public function __construct(Workflow $workflow,
                                ObjectManager $objectManager,
                                SessionInterface $session,
                                EventDispatcherInterface $dispatcher,
                                Serializer $serializer
                                )
    {
        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository(Purchase::class);
        $this->session = $session;
        $this->dispatcher = $dispatcher;
        $this->serializer = $serializer;
        $this->workflow = $workflow;
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
     * @param ResponseInterface $response
     * @return $this
     */
    public function thread(Purchase $purchase, ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();
        $data = $this->serializer->deserialize($content, 'array', 'json');
        $return = array();
        $return['status'] = $data['status_code'];
        $return['message'] = $data['message'];
        if ($data['status_code'] === 400)
        {
            $this->setErrorStatus($purchase);
            $this->session->getFlashBag()->add('danger', $data['message']);
        }

        if(isset($data['request_id'])) {

            $this->setRequestedStatus($purchase, $data['request_id']);
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
        if(!$this->workflow->can($purchase, 'error'))
        {
            throw new Exception('Purchase cannot be as error status');
        }
        $this->workflow->apply($purchase, 'error');
        $purchase->setUpdatedAt(new \DateTime());
        $this->save($purchase);
        return $this;

    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function makeRequest(Purchase $purchase)
    {
        $quantity = $purchase->getQuantity();
        $user = $purchase->getRequester();


        if(null === $user) {
            $this->setErrorStatus($purchase);
            throw new Exception('No requester found on this purchase');
        }
        if(null === $user->getCenter()) {
            $this->setErrorStatus($purchase);
            throw new Exception('No center associated on current user');
        }

        if(!$quantity) {
            $this->setErrorStatus($purchase);
            throw new Exception('Qunatity cannot be null');
        }

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
            'center' => $user->getCenter()->getCode(),
            'quantity' => $quantity
        );
        $jsonData = $this->serializer->serialize($data, 'json');
        $response = $client->request('POST', 'request', array(
            'body' => $jsonData
        ));

        $this->thread($purchase, $response);

        return $this;
    }
}
