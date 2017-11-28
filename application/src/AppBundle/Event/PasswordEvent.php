<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 27/11/2017
 * Time: 21:57
 */

namespace AppBundle\Event;

use AppBundle\Entity\Customer;
use AppBundle\Entity\RequestPassword;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class PasswordEvent extends Event
{
    const FORGOTTEN = 'forgotten.event';
    const CHANGE_PASSWORD = 'change_password.event';

    /**
     * @var RequestPassword
     */
    private $requestPassword;

    /**
     * RegisterEvent constructor.
     * @param Customer $customer
     * @param User $user
     */
    public function __construct(RequestPassword $requestPassword)
    {

        $this->requestPassword = $requestPassword;
    }

    /**
     * @return RequestPassword
     */
    public function getRequestPassword()
    {
        return $this->requestPassword;
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->requestPassword->getUser();
    }
}