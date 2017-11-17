<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\CustomerGame;
use AppBundle\Entity\Game;
use AppBundle\Form\CustomerGameType;
use AppBundle\Form\GameType;
use AppBundle\Manager\CardManager;
use AppBundle\Manager\CustomerGameManager;
use AppBundle\Manager\GameManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class GameController extends Controller
{
    /**
     * @Route("/manage/games", name="game_list")
     */
    public function listAction(GameManager $gameManager)
    {
        $games = $gameManager->getList();
        return $this->render('AppBundle:Game:list.html.twig', array(
            'games' => $games
        ));
    }

    /**
     * @Route("/manage/game/create", name="game_create")
     */
    public function createAction(Request $request, GameManager $gameManager)
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            if($gameManager->insert($game)) {
                return $this->redirectToRoute('game_edit', array('id'=>$game->getId()));
            }
        }

        return $this->render('AppBundle:Game:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/manage/game/{id}/edit", name="game_edit")
     *
     */
    public function editAction(Game $game, Request $request)
    {

        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        return $this->render('AppBundle:Game:edit.html.twig', array(
            'form' => $form->createView(),
            'game' => $game
        ));
    }

    /**
     * @Route("/manage/game/{id}/record", name="game_record")
     *
     */
    public function recordAction(Game $game, GameManager $gameManager)
    {
        $gameManager->record($game);
        return $this->redirectToRoute('game_manage', array('id' => $game->getId()));
    }

    /**
     * @param Request $request
     * @Route("/manage/game/{id}/manage", name="game_manage")
     * @Method({"GET"})
     */
    public function manageAction(Game $game, CustomerGameManager $customerGameManager)
    {
        $card = new Card();
        $form = $this->createForm(CustomerGameType::class, $card);
        $customersGame = $customerGameManager->getCustomersByGame($game);

        return $this->render('AppBundle:Game:manage.html.twig', array(
            'game' => $game,
            'form' => $form->createView(),
            'customersGame' => $customersGame
        ));
    }

    /**
     * @param Request $request
     * @Route("/manage/game/{id}/addUser", name="game_add_user")
     * @Method({"POST"})
     */
    public function addUserAction(Game $game, Request $request, CardManager $cardManager, CustomerGameManager $customerGameManager)
    {
        $numero = $request->request->get('numero');
        $card = $cardManager->search($numero);
        $customerGameManager->add($card->getCustomer(), $game);
        return $this->redirectToRoute('game_manage', array('id' => $game->getId()));
    }

    /**
     * @param Request $request
     * @Route("/manage/game/{id}/removeUser/{customer_id}", name="game_remove_user")
     * @Method({"POST"})
     */
    public function removeUserAction(Game $game, Customer $customer, CustomerGameManager $customerGameManager)
    {

        $customerGameManager->remove($customer, $game);
        return $this->redirectToRoute('game_manage', array('id' => $game->getId()));
    }

    /**
     * @Route("/manage/game/{id}/delete", name="game_delete")
     */
    public function deleteAction($id)
    {
        return $this->render('AppBundle:Game:delete.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/manage/game/search", name="game_search")
     * @Method({"POST"})
     */
    public function searchAction(Request $request, CardManager $cardManager)
    {
        $numero = $request->request->get('numero');

        $card = $cardManager->search($numero);

        $response = [
            'status' => 0,
            'message' => 'No customer found with number '.$numero,
            'data' => null
        ];

        if($card instanceof Card && null !== $card->getCustomer())
        {
            $response['status'] = 1;
            $response['message'] = 'Customer found';
            $response['data'] = $this->renderView('AppBundle:Game:customer.html.twig', array(
                'card' => $card
            ));
        }
        //return $this->render('AppBundle:Game:customer.html.twig', array('card' => $card));
        return $this->json($response);
    }
}
