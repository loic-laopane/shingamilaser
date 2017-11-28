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
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    private $from;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct($from,
                                ObjectManager $objectManager,
                                EventDispatcherInterface $dispatcher,
                                PlayerManager $playerManager,
                                \Swift_Mailer $mailer,
                                TranslatorInterface $translator,
                                EngineInterface $templating)
    {
        $this->objectManager = $objectManager;
        $this->dispatcher = $dispatcher;
        $this->playerManager = $playerManager;
        $this->mailer = $mailer;
        $this->from = $from;
        $this->translator = $translator;
        $this->templating = $templating;
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
        $user = $event->getUser();
        $customer = $event->getCustomer();
        $game = $event->getGame();
        $message = new \Swift_Message($this->translator->trans('Offer unlocked'));
        $message->setFrom($this->from)
            ->setTo($user->getEmail())
            ->setBody($this->templating->render('AppBundle:Mail:offer-unlocked.html.twig', array(
                'user' => $user,
                'customer' => $customer,
                'game' => $game
            )),
                'text/html');
        $this->mailer->send($message);
    }
}