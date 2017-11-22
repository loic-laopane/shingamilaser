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
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GameController
 * @package AppBundle\Controller
 * @Route("/staff")
 */
class GameController extends Controller
{
    /**
     * @Route("/games", name="game_list")
     */
    public function listAction(GameManager $gameManager)
    {
        $games = $gameManager->getList();
        return $this->render('AppBundle:Game:list.html.twig', array(
            'games' => $games
        ));
    }

    /**
     * @Route("/game/create", name="game_create")
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
     * @Route("/game/{id}/edit", name="game_edit")
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
     * @Route("/game/{id}/record", name="game_record")
     *
     */
    public function recordAction(Game $game, GameManager $gameManager)
    {
        $gameManager->record($game);
        return $this->redirectToRoute('game_manage', array('id' => $game->getId()));
    }

    /**
     * @param Request $request
     * @Route("/game/{id}/manage", name="game_manage")
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
     * @Route("/game/{id}/addUser", name="game_add_user")
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
     * @Route("/game/{id}/removeUser/{customer_id}", name="game_remove_user")
     * @Method({"POST"})
     */
    public function removeUserAction(Game $game, Customer $customer, CustomerGameManager $customerGameManager)
    {

        $customerGameManager->remove($customer, $game);
        return $this->redirectToRoute('game_manage', array('id' => $game->getId()));
    }

    /**
     * @Route("/game/{id}/delete", name="game_delete")
     */
    public function deleteAction($id)
    {
        return $this->render('AppBundle:Game:delete.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/game/search", name="game_search")
     * @Method({"GET", "POST"})
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

    /**
     * @Route("/qrcode", name="game_qrcode")
     */
    public function qrcodeAction(ObjectManager $objectManager, Request $request, CustomerGameManager $customerGameManager, CardManager $cardManager)
    {
        $return = [];
        $numero = $request->request->get('qrData');
        $game_id = $request->request->get('game');
        $game = $objectManager->getRepository('AppBundle:Game')->find($game_id);
        $card = $cardManager->search($numero);

        if(null === $card) {
            $return['status'] = 0;
            $return['message'] = 'Card not found';
        }
        else {
            if(null === $card->getCustomer())
            {
                $return['status'] = 0;
                $return['message'] = 'Customer not found';
            }
            else {
                $customerGameManager->add($card->getCustomer(), $game);
                $return['status'] = 1;
                $return['message'] = 'User found';
            }
        }

        return $this->json($return);

    }
}
