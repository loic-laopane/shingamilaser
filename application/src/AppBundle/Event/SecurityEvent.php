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
    const CREATE_USER = 'create_user.event';
    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var User
     */
    private $user;

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
