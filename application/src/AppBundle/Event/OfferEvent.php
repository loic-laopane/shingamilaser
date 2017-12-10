<?php

namespace AppBundle\Event;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Game;
use AppBundle\Entity\User;
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

    /**
     * @var User
     */
    private $user;

    public function __construct()
    {

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
        return $this->user;
    }

    public function setGame(Game $game)
    {
        $this->game = $game;

        return $this;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        $user = $this->customer->getUser();
        if (null !== $user) {
            $this->setUser($user);
        }

        return $this;
    }

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

}
