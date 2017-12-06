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
use AppBundle\Entity\CustomerOffer;
use AppBundle\Entity\Game;
use AppBundle\Entity\Offer;
use AppBundle\Entity\User;
use AppBundle\Event\OfferEvent;
use AppBundle\Event\SecurityEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(
        ObjectManager $manager,
                                UserManager $userManager,
                                EventDispatcherInterface $dispatcher
    ) {
        $this->manager = $manager;
        $this->userManager = $userManager;
        $this->repository = $this->manager->getRepository(Customer::class);
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Customer $customer
     * Ajout un client en base, avec son utilisateur
     */
    public function register(Customer $customer)
    {
        $user = clone $customer->getUser();
        $this->userManager->encodeUserPassword($customer->getUser());

        if ($this->userManager->mailExists($customer->getUser())) {
            throw new \Exception('This email is already used');
        }
        $this->manager->persist($customer);
        $this->manager->flush();

        $this->dispatcher->dispatch(SecurityEvent::REGISTER, new SecurityEvent($customer, $user));

        return $this;
    }

    /**
     * @param Customer $customer
     * @return $this
     */
    public function save(Customer $customer)
    {
        if (!$this->manager->contains($customer)) {
            $this->manager->persist($customer);
        }
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
        if (null === $cards) {
            $cards = new ArrayCollection();
        }
        return $cards;
    }

    public function getCustomerByParams(array $params)
    {
        $customers = $this->repository->findByParams($params);
        return $customers;
    }

    public function removeAvatar(Customer $customer)
    {
        if (null !== $customer->getAvatar()) {
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

    /**
     * @param Offer $offer
     * @return mixed
     */
    public function getCountUnlockOffer(Customer $customer, Offer $offer)
    {
        return $this->manager->getRepository(CustomerOffer::class)->getCountUnlockOffer($customer, $offer);
    }

    public function unlockOffer(Customer $customer, Offer $offer, Game $game)
    {
        $customerOffer = new CustomerOffer();
        $customerOffer->setCustomer($customer)
            ->setOffer($offer);
        $this->manager->persist($customerOffer);
        $this->manager->flush();

        $this->dispatcher->dispatch(OfferEvent::UNLOCKED_EVENT, new OfferEvent($game, $customer));
    }

    /**
     * @param array $params
     * @throws \Exception
     */
    public function checkSearchParams(array $params)
    {
        foreach ($params as $field => $val) {
            if (!empty($val)) {
                return;
            }
        }
        throw new \Exception('alert.one_field_required');
    }
}
