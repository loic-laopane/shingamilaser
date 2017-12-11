<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/11/2017
 * Time: 10:42
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;


class CardManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;


    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository(Card::class);
    }

    /**
     * Try to attach a card to a customer by numero
     * @param $numero
     * @param Customer $customer
     * @return $this
     */
    public function rattach($numero, Customer $customer)
    {
        $card = $this->repository->findOneBy(['numero' => $numero]);
        if (!$card instanceof Card) {
            throw new Exception('Card '.$numero.' doesn\'t exist');
        }


        if ($card->hasOwner()) {
            if ($card->getCustomer() === $customer) {
                throw new Exception('Card '.$numero.' is already attached to your account');
            } else {
                throw new Exception('Card '.$numero.' has already an owner');
            }
        }

        if ($card->getActive() === false) {
            throw new Exception('Card '.$numero.' is not active');
        }

        //Desactivation des cards
        $this->unactiveCards($customer);

        $this->attach($card, $customer);

        return $this;
    }

    /**
     * attache a card with a customer
     * @param Card $card
     * @param Customer $customer
     */
    public function attach(Card $card, Customer $customer)
    {
        $card->setActivatedAt(new \DateTime());
        $card->setCustomer($customer);
        $this->save($card);
        return $this;
    }

    /**
     * @param Card $card
     * @return $this
     */
    public function save(Card $card)
    {
        if(!$this->objectManager->contains($card)) {
            $this->objectManager->persist($card);
        }
        $this->objectManager->flush();
        return $this;
    }

    /**
     * @param $numero
     * @return Card|null|object
     */
    public function search($numero)
    {
        return $this->repository->findOneBy(['numero' => $numero]);
    }

    /**
     * @param Customer $customer
     */
    private function unactiveCards(Customer $customer)
    {
        foreach ($customer->getCards() as $card) {
            $card->setActive(false);
            $this->objectManager->persist($card);
        }
        $this->objectManager->flush();

        return $this;
    }

    /**
     * @param Card $card
     * @param User $user
     * @throws \Exception
     * @return $this
     */
    public function checkUser(Card $card, User $user)
    {
        $customer = $card->getCustomer();
        if(null === $customer)
        {
            throw new \Exception('alert.card_error');
        }
        $account = $customer->getUser();
        if($account !== $user)
        {
            throw new \Exception('This card is not yours');
        }

        return $this;
    }
}
