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
use AppBundle\Manager\CustomerManager;
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
    /**
     * @var CustomerManager
     */
    private $customerManager;

    public function __construct(
        $from,
                                ObjectManager $objectManager,
                                EventDispatcherInterface $dispatcher,
                                PlayerManager $playerManager,
                                CustomerManager $customerManager,
                                \Swift_Mailer $mailer,
                                TranslatorInterface $translator,
                                EngineInterface $templating
    ) {
        $this->objectManager = $objectManager;
        $this->dispatcher = $dispatcher;
        $this->playerManager = $playerManager;
        $this->mailer = $mailer;
        $this->from = $from;
        $this->translator = $translator;
        $this->templating = $templating;
        $this->customerManager = $customerManager;
    }

    /**
     * @param OfferEvent $event
     */
    public function onOfferUnlockable(OfferEvent $event)
    {
        $customer = $event->getCustomer();
        $game = $event->getGame();

        //Liste des parties du client avec une carte
        $customerGamesWithCard = $this->playerManager->getGamesCustomerWithCard($customer);

        //Liste des offres deja recu
        $customerOffers = $customer->getCustomerOffers();

        //Liste de toutes les offres disponibles
        $offers = $this->objectManager->getRepository(Offer::class)->getActiveOffers();

        foreach ($offers as $offer) {
            //Nombre de fois que cette $offer est deblocable
            $nbUnlockableOffers = floor(count($customerGamesWithCard) / $offer->getCount());

            //nombre de fois que le client a debloqué cette $offer
            $nbUnlockedOffer = $this->customerManager->getCountUnlockOffer($customer, $offer);

            //Delta entre le nombre fois que $offer est deblocable et le nombre de fois $offer a ete debloquée
            $nbCanUnlock = $nbUnlockableOffers - $nbUnlockedOffer;

            //Si le delta est > 0
            if ($nbCanUnlock > 0) {
                //On debloque l'offre autant de fois que nécessaire
                for ($i = 0; $i < $nbCanUnlock; $i++) {
                    $this->customerManager->unlockOffer($customer, $offer, $game);
                }
            }
        }
    }

    /**
     * Envoi d'un  mail au Customer lors du deblocage d'une Offer
     * @param OfferEvent $event
     */
    public function onOfferUnlocked(OfferEvent $event)
    {
        $user = $event->getUser();
        $customer = $event->getCustomer();
        $game = $event->getGame();
        $message = new \Swift_Message($this->translator->trans('Offer unlocked'));
        $message->setFrom($this->from)
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('AppBundle:Mail:offer-unlocked.html.twig', array(
                'user' => $user,
                'customer' => $customer,
                'game' => $game
            )),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
