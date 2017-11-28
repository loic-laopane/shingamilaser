<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 28/11/2017
 * Time: 14:12
 */

namespace AppBundle\Event\Listener;


use AppBundle\Event\OfferEvent;

class OfferListener
{
    public function __construct()
    {
    }

    public function onOfferUnlocked(OfferEvent $event)
    {
        $customer = $event->getCustomer();

    }
}