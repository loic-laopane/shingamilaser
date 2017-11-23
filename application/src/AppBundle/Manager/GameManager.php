<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:46
 */

namespace AppBundle\Manager;

use AppBundle\Entity\CustomerGame;
use AppBundle\Entity\Game;
use Doctrine\Common\Persistence\ObjectManager;
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


    public function __construct(ObjectManager $manager, SessionInterface $session, EngineInterface $templating, CustomerManager $customerManager)
    {
        $this->manager = $manager;
        $this->repository = $manager->getRepository(Game::class);
        $this->session = $session;
        $this->templating = $templating;
        $this->customerManager = $customerManager;
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
            $this->simuleScore($game);
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

        $numero = $data['numero'];
        $nickname = $data['nickname'];
        $response = [
            'status' => 0,
            'message' => 'No customer found with number '.$numero,
            'data' => null
        ];

        if(empty($numero) && empty($nickname))
        {
            $response['message'] = 'Please fill card number or customer nickname';
        }
        else {
            $customers = $this->customerManager->getCustomerByParams($data);
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
        $customerGame = $this->manager->getRepository(CustomerGame::class)->findBy(['game' => $game]);
        foreach($customerGame as $row)
        {
            $row->setScore(mt_rand(0, 100));
        }
        $this->manager->flush();

        return $this;
    }

}