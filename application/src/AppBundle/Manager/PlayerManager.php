<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:46
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Player;
use AppBundle\Entity\Game;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PlayerManager
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
     * CustomerGameManager constructor.
     * @param ObjectManager $manager
     * @param SessionInterface $session
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository(Player::class);
    }

    /**
     * @param Player $customerGame
     * @return Player|null|object
     */
    public function exists(Player $player)
    {
        return $this->repository->findOneBy(array(
            'customer' => $player->getCustomer(),
            'game' => $player->getGame()
        ));
    }

    /**
     * Insert Game in DB
     * @param Game $game
     * @return $this
     */
    public function insert(Player $player)
    {
        $this->manager->persist($player);
        $this->manager->flush();

        return $this;
    }

    /**
     * @param Game $game
     * @param Customer $customer
     * @return $this
     */
    public function add(Customer $customer, Game $game)
    {
        $player = new Player();
        $player->setGame($game);
        $player->setCustomer($customer);
        $card = $this->manager->getRepository(Card::class)->findCustomerActiveCard($customer);
        if (null !== $card) {
            $player->setCard($card);
        }

        if ($this->exists($player)) {
            throw new \Exception('alert.customer.already_in_game');
        }

        $this->insert($player);

        return $this;
    }

    /**
     * @param Game $game
     * @param Customer $customer
     * @return $this
     */
    public function remove(Customer $customer, Game $game)
    {
        $player = $this->repository->findOneBy(array(
            'customer' => $customer,
            'game' => $game
        ));

        if ($player) {
            $this->manager->remove($player);
            $this->manager->flush();
        }

        return $this;
    }

    /**
     * @return Player[]|array
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

    public function getGamesCustomerWithCard(Customer $customer)
    {
        return $this->repository->getGamesCustomerWithCard($customer);
    }
}
