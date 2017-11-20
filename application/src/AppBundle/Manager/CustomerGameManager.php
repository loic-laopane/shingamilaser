<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:46
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Customer;
use AppBundle\Entity\CustomerGame;
use AppBundle\Entity\Game;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CustomerGameManager
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var \AppBundle\Repository\GameRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * CustomerGameManager constructor.
     * @param ObjectManager $manager
     * @param SessionInterface $session
     */
    public function __construct(ObjectManager $manager, SessionInterface $session)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository(CustomerGame::class);
        $this->session = $session;
    }

    /**
     * @param CustomerGame $customerGame
     * @return CustomerGame|null|object
     */
    public function exists(CustomerGame $customerGame)
    {
        return $this->repository->findOneBy(array(
            'customer' => $customerGame->getCustomer(),
            'game' => $customerGame->getGame()
        ));
    }

    /**
     * Insert Game in DB
     * @param Game $game
     * @return bool
     */
    public function insert(CustomerGame $customerGame)
    {
        $this->manager->persist($customerGame);
        $this->manager->flush();
        $this->session->getFlashBag()->add('success', 'Customer has been added to the Game');
        return true;
    }

    /**
     * @param Game $game
     * @param Customer $customer
     * @return $this
     */
    public function add(Customer $customer, Game $game)
    {
        $customerGame = new CustomerGame();
        $customerGame->setGame($game);
        $customerGame->setCustomer($customer);

        if($this->exists($customerGame))
        {
            $this->session->getFlashBag()->add('danger', 'this customer is already in this game');
            return false;
        }

        $this->insert($customerGame);

        return $this;
    }

    /**
     * @param Game $game
     * @param Customer $customer
     * @return $this
     */
    public function remove(Customer $customer, Game $game)
    {
        $customerGame = $this->repository->findOneBy(array(
            'customer' => $customer,
            'game' => $game
        ));

        if($customerGame) {
            $this->manager->remove($customerGame);
            $this->manager->flush();

            $this->session->getFlashBag()->add('success', 'Customer '.$customer->getNickname().' has been removed from this game');
        }

    }

    /**
     * @return CustomerGame[]|array
     */
    public function getList()
    {
        return $this->repository->findAll();
    }

    /**
     * @param Game $game
     * @return array
     */
    public function getCustomersByGame(Game $game)
    {
        return $this->repository->getCustomersByGame($game);
    }


}