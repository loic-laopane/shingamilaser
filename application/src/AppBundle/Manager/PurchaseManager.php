<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/11/2017
 * Time: 10:42
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Purchase;
use AppBundle\Event\PurchaseEvent;
use Doctrine\Common\Persistence\ObjectManager;

use JMS\Serializer\Serializer;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

    public function __construct(ObjectManager $objectManager,
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

    public function search($numero)
    {
       return $this->repository->findOneBy(['numero' => $numero]);
    }

    /**
     * @param
     */
    public function thread(Purchase $purchase, ResponseInterface $response)
    {
        $headers = $response->getHeaders();
        $content = $response->getBody()->getContents();;
        dump($response);
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

    private function setRequestedStatus(Purchase $purchase, $reference)
    {
        $purchase->setStatus('requested');
        $purchase->setReference($reference);
        $purchase->setUpdatedAt(new \DateTime());
        $this->save($purchase);
    }
    private function setErrorStatus(Purchase $purchase)
    {
        $purchase->setStatus('error');
        $purchase->setUpdatedAt(new \DateTime());
        $this->save($purchase);

        return $this;

    }

}