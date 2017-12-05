<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:46
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Player;
use AppBundle\Entity\Game;
use AppBundle\Event\OfferEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Templating\EngineInterface;

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
    /**
     * @var EngineInterface
     */
    private $templating;
    /**
     * @var CustomerManager
     */
    private $customerManager;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;


    public function __construct(ObjectManager $manager,
                                SessionInterface $session,
                                EngineInterface $templating,
                                CustomerManager $customerManager,
                                EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository(Game::class);
        $this->session = $session;
        $this->templating = $templating;
        $this->customerManager = $customerManager;
        $this->dispatcher = $dispatcher;
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
    }

    public function save(Game $game)
    {
        if(!$this->manager->contains($game))
        {
            $this->manager->persist($game);
        }
        $this->manager->flush();

        $this->session->getFlashBag()->add('success', 'Game updated');
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
        if(null !== $game->getStartedAt()) {
            $this->stopGame($game);
        }
        else {
            $this->startGame($game);
        }
        return $this;
    }

    /**
     * @param Game $game
     * @return $this
     */
    private function startGame(Game $game)
    {
        //Verifier qu'il y ait minimum 2 joueurs
        if(count($game->getPlayers()) < 2)
        {
            throw new \Exception('A game must have at least 2 players');
        }
        $game->setStartedAt(new \DateTime());
        $this->manager->flush();
        return $this;
    }

    /**
     * @param Game $game
     * @return $this
     */
    private function stopGame(Game $game)
    {
        $game->setEndedAt(new \DateTime());
        $this->simuleScore($game);
        $this->manager->flush();
        return $this;
    }

    /**
     * @return Game[]|array
     */
    public function getList()
    {
        return $this->repository->findAll();
    }

    /**
     * @param $page
     * @param $max
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getListWithPage($page, $max)
    {
        return $this->repository->getListWithPage($page, $max);
    }

    /**
     * @return mixed
     */
    public function countAll()
    {
        return $this->repository->countAll();
    }


    /**
     * Recherche un Client d'apres des parametre venant d'un formulaire
     * Retourne un reponse
     * Si reponse positive, retourne une vue incluant le game
     * @param array $data
     * @param Game $game
     * @return array
     */
    public function searchCustomerWithGame(array $data, Game $game)
    {
        $required_fields = ['numero', 'lastname', 'firstname', 'nickname'];
        $fields_valid = false;
        foreach($required_fields as $field)
        {
            if(isset($data[$field]) && !empty($data[$field]))
            {
                $fields_valid = true;
                break;
            }
        }

        $response = [
            'status' => 0,
            'message' => 'No customer found',
            'data' => null
        ];

        if(!$fields_valid)
        {
            $response['message'] = 'Please fill at least one search field';
        }
        else {
            $customers = $this->customerManager->getCustomerByParams($data);
            //$response['message'] = $customers;
            //return $response;
            if(count($customers) == 0) {
                $response['message'] = 'No customer found';
            }
            else {
                foreach($customers  as $customer)
                {
                    $response['data'] .= $this->templating->render('AppBundle:Game:customer.html.twig', array(
                        'customer' => $customer,
                        'game' => $game
                    ));
                }
                $response['status'] = 1;
                $response['message'] = 'Customer found';

            }
        }
        return $response;
    }

    /**
     * @param Game $game
     * @return $this
     */
    public function simuleScore(Game $game)
    {
        $players = $this->manager->getRepository(Player::class)->findBy(['game' => $game]);
        foreach($players as $player)
        {
            $player->setScore(mt_rand(0, 100));
            $this->dispatcher->dispatch(OfferEvent::UNLOCKABLE_EVENT, new OfferEvent($game, $player->getCustomer()));
        }
        $this->manager->flush();

        return $this;
    }
}