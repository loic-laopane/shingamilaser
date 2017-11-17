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
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(ObjectManager $objectManager, SessionInterface $session)
    {
        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository(Card::class);
        $this->session = $session;
    }

    /**
     * Try to attach a card to a customer by numero
     * @param $numero
     * @param Customer $customer
     * @return bool
     */
    public function rattach($numero, Customer $customer)
    {
        $card = $this->repository->findOneBy(['numero' => $numero]);
        if(!$card instanceof Card) {
            $this->session->getFlashBag()->add('danger', 'Card '.$numero.' doesn\'t exist');
            return false;
        }

        if($card->hasOwner())
        {
            if($card->getCustomer() === $customer) {
                $this->session->getFlashBag()->add('danger', 'Card '.$numero.' is already attached to your account');
            }
            else {
                $this->session->getFlashBag()->add('danger', 'Card '.$numero.' has already an owner');
            }
            return false;
        }

        $this->attach($card, $customer);
        $this->session->getFlashBag()->add('success', 'Card '.$numero.' has been attached to your account');

        return true;
    }

    /**
     * attache a card with a customer
     * @param Card $card
     * @param Customer $customer
     */
    public function attach(Card $card, Customer $customer)
    {
        $card->setCustomer($customer);
        $this->save($card);
    }

    /**
     * @param Card $card
     * @return $this
     */
    public function save(Card $card)
    {
        $this->objectManager->persist($card);
        $this->objectManager->flush();
        return $this;
    }

    public function search($numero)
    {
       return $this->repository->findOneBy(['numero' => $numero]);
    }


}