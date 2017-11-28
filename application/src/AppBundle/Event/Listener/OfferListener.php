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
use AppBundle\Manager\PlayerManager;
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
    /**
     * @var PlayerManager
     */
    private $playerManager;

    public function __construct(ObjectManager $objectManager,
                                EventDispatcherInterface $dispatcher, PlayerManager $playerManager)
    {
        $this->objectManager = $objectManager;
        $this->dispatcher = $dispatcher;
        $this->playerManager = $playerManager;
    }

    /**
     * @param OfferEvent $event
     */
    public function onOfferUnlockable(OfferEvent $event)
    {
        $customer = $event->getCustomer();
        $game = $event->getGame();
        //$customerGames = $customer->getCustomerGames();
        $customerGamesWithCard = $this->playerManager->getGamesCustomerWithCard($customer);
        $customerOffers = $customer->getCustomerOffers();
        $offers = $this->objectManager->getRepository(Offer::class)->getActiveOffers();

        $nbGameWithCard = count($customerGamesWithCard);
        $unlockedCustomerOffers = count($customerOffers);
        foreach ($offers as $offer)
        {
            $nbUnlockableOffers = floor($nbGameWithCard / $offer->getCount());//1
            $isUnlockable = $nbUnlockableOffers - $unlockedCustomerOffers;//0
            if($isUnlockable)
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

    public function onOfferUnlocked(OfferEvent $event)
    {

    }
}