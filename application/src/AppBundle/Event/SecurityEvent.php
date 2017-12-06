<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 27/11/2017
 * Time: 21:57
 */

namespace AppBundle\Event;

use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class SecurityEvent extends Event
{
    const REGISTER = 'register.event';
    const CHANGE_PASSWORD = 'change_password.event';
    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var User
     */
    private $user;

    /**
     * RegisterEvent constructor.
     * @param Customer $customer
     * @param User $user
     */
    public function __construct(Customer $customer, User $user)
    {
        $this->customer = $customer;
        $this->user = $user;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
