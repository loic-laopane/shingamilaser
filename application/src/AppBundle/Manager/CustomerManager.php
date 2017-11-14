<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:38
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
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

    /**
     * @var
     */
    private $repository;

    public function __construct(ObjectManager $manager, UserManager $userManager)
    {
        $this->manager = $manager;
        $this->userManager = $userManager;
        $this->repository = $this->manager->getRepository(Customer::class);
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

        return $this;
    }

    /**
     * @param Customer $customer
     * @return $this
     */
    public function save(Customer $customer)
    {
        $this->manager->persist($customer);
        $this->manager->flush();
        return $this;
    }
    /**
     * @param User $user
     * @return Customer
     */
    public function getCustomerByUser(User $user)
    {
        return $this->repository->findOneByUser($user);
    }
}