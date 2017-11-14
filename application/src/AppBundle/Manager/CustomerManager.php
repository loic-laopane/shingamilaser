<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:38
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Customer;
use Doctrine\Common\Persistence\ObjectManager;

class CustomerManager
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(ObjectManager $manager, UserManager $userManager)
    {
        $this->manager = $manager;
        $this->userManager = $userManager;
    }

    /**
     * @param Customer $customer
     * Ajout un client en base, avec son utilisateur
     */
    public function register(Customer $customer)
    {
        $this->userManager->encodeUserPassword($customer->getUser());
        $this->manager->persist($customer);
        $this->manager->flush();
    }
}