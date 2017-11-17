<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:46
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Game;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameManager
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


    public function __construct(ObjectManager $manager, SessionInterface $session)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository(Game::class);
        $this->session = $session;
    }


    /**
     * Insert Game in DB
     * @param Game $game
     * @return bool
     */
    public function insert(Game $game)
    {

        $this->manager->persist($game);
        $this->manager->flush();

        $this->session->getFlashBag()->add('success', 'Game created');
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $game = $this->repository->find($id);
        if (null === $game){
            $this->session->getFlashBag()->add('danger', 'This Center doesn\'t exist');
            return false;
        }

        $title = $game->getTitle();
        $this->manager->remove($game);
        $this->manager->flush();

        $this->session->getFlashBag()->add('success', 'Game '.$title.' has been deleted');
        return true;
    }

    public function record(Game $game)
    {
        $now = new \DateTime();
        if(null !== $game->getStartedAt()) {
            $game->setEndedAt($now);
        }
        else {
            $game->setStartedAt($now);
        }

        $this->manager->flush();
        return $this;
    }

    /**
     *
     */
    public function getList()
    {
        return $this->repository->findAll();
    }


}