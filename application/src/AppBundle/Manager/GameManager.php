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
use Symfony\Component\Translation\TranslatorInterface;

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
    /**
     * @var TranslatorInterface
     */
    private $translator;


    public function __construct(ObjectManager $manager,
                                EngineInterface $templating,
                                CustomerManager $customerManager,
                                EventDispatcherInterface $dispatcher,
                                TranslatorInterface $translator
    ) {
        $this->manager = $manager;
        $this->repository = $manager->getRepository(Game::class);
        $this->templating = $templating;
        $this->customerManager = $customerManager;
        $this->dispatcher = $dispatcher;
        $this->translator = $translator;
    }


    /**
     * Insert Game in DB
     * @param Game $game
     * @return $this
     */
    public function insert(Game $game)
    {
        $this->manager->persist($game);
        $this->manager->flush();

        return $this;
    }

    /**
     * @param Game $game
     * @return $this
     */
    public function save(Game $game)
    {
        if (!$this->manager->contains($game)) {
            $this->manager->persist($game);
        }
        $this->manager->flush();
        return $this;
    }

    /**
     * @param Game $game
     * @return $this
     */
    public function record(Game $game)
    {
        if (null !== $game->getStartedAt()) {
            $this->stopGame($game);
        } else {
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
        if (count($game->getPlayers()) < 2) {
            throw new \Exception('alert.game_must_have_2_players');
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
        foreach ($required_fields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $fields_valid = true;
                break;
            }
        }

        $response = [
            'status' => 0,
            'message' => $this->translator->trans('alert.customer.not_found'),
            'data' => null
        ];

        if (!$fields_valid) {
            $response['message'] = $this->translator->trans('alert.search.fill_field');
        } else {
            $customers = $this->customerManager->getCustomerByParams($data);
            //$response['message'] = $customers;
            //return $response;
            if (count($customers) == 0) {
                $response['message'] = $this->translator->trans('alert.customer.not_found');
            } else {
                foreach ($customers  as $customer) {
                    $response['data'] .= $this->templating->render('AppBundle:Game:customer.html.twig', array(
                        'customer' => $customer,
                        'game' => $game
                    ));
                }
                $response['status'] = 1;
                $response['message'] = $this->translator->trans('alert.customer.found');
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
        foreach ($players as $player) {
            $player->setScore(mt_rand(0, 100));
            $offerEvent = new OfferEvent();
            $offerEvent->setGame($game)
                        ->setCustomer($player->getCustomer());
            $this->dispatcher->dispatch(OfferEvent::UNLOCKABLE_EVENT, $offerEvent);
        }
        $this->manager->flush();

        return $this;
    }
}
