<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:38
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

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
     * @var EntityRepository
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

        if($this->userManager->mailExists($customer->getUser())) {
            throw new \Exception('This email is already used');
        }
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

    /**
     * Get all cards of one customer
     * @param Customer $customer
     * @return ArrayCollection
     */
    public function getCustomerCards(Customer $customer)
    {
        $cards = $this->manager->getRepository(Card::class)->findByCustomer($customer);
        if(null === $cards)
        {
            $cards = new ArrayCollection();
        }
        return $cards;
    }

    public function getCustomerByParams(array $params)
    {
        $customers = $this->repository->findByParams($params);
        return $customers;
    }

    public function removeAvatar(Customer $customer) {

        if(null !== $customer->getAvatar())
        {
            $this->manager->remove($customer->getAvatar());
            $customer->setAvatar(null);
            $this->manager->flush();
        }
    }

    /**
     * @param Customer $customer
     * @param User $user
     * @return $this
     */
    public function quickCreate(Customer $customer, User $user)
    {
        $user->setUsername($user->getEmail());
        $user->setPassword(substr(uniqid(), 0, 6));
        $this->userManager->encodeUserPassword($user);
        $user->setRoles(['ROLE_USER']);
        $customer->setUser($user);

        $this->register($customer);

        return $this;
    }


}