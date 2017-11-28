<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 28/11/2017
 * Time: 14:12
 */

namespace AppBundle\Event\Listener;


use AppBundle\Entity\CustomerOffer;
use AppBundle\Entity\Offer;
use AppBundle\Event\OfferEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OfferListener
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(ObjectManager $objectManager, EventDispatcherInterface $dispatcher)
    {
        $this->objectManager = $objectManager;
        $this->dispatcher = $dispatcher;
    }

    public function onOfferUnlocked(OfferEvent $event)
    {
        $customer = $event->getCustomer();
        $game = $event->getGame();
        $customerGames = $customer->getCustomerGames();
        $customerOffers = $customer->getCustomerOffers();
        $offers = $this->objectManager->getRepository(Offer::class)->getActiveOffers();

        $nb_game = count($customerGames);
        $nb_unlocked_offer = count($customerOffers);
        foreach ($offers as $offer)
        {
            $nb_unlockable = floor($nb_game / $offer->getCount());//1
            $is_unlock = $nb_unlockable - $nb_unlocked_offer;//0
            if($is_unlock)
            {
                $customerOffer = new CustomerOffer();
                $customerOffer->setCustomer($customer)
                            ->setOffer($offer);
                $this->objectManager->persist($customerOffer);
                $this->objectManager->flush();

                //Todo send an email
                //Todo send a flash message
                //dispatch event
                $this->dispatcher->dispatch(OfferEvent::UNLOCKED_EVENT, new OfferEvent($game, $customer));
            }
        }
    }
}