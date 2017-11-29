<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 28/11/2017
 * Time: 14:08
 */

namespace AppBundle\Event;


use AppBundle\Entity\Customer;
use AppBundle\Entity\Game;
use Symfony\Component\EventDispatcher\Event;

class OfferEvent extends Event
{

    const UNLOCKABLE_EVENT = 'offer.unlockable.event';
    const UNLOCKED_EVENT = 'offer.unlocked.event';
    /**
     * @var Game
     */
    private $game;
    /**
     * @var Customer
     */
    private $customer;

    public function __construct(Game $game, Customer $customer)
    {
        $this->game = $game;
        $this->customer = $customer;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function getUser()
    {
        return $this->customer->getUser();
    }
}