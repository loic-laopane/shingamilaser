<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Game;
use AppBundle\Form\CustomerAddCardType;
use AppBundle\Form\CustomerGameType;
use AppBundle\Form\GameType;
use AppBundle\Manager\CardManager;
use AppBundle\Manager\PlayerManager;
use AppBundle\Manager\CustomerManager;
use AppBundle\Manager\GameManager;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
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
        try {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $gameManager->insert($game);
                return $this->redirectToRoute('game_edit', array('id'=>$game->getId()));

            }
        }
        catch(\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->render('AppBundle:Game:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/game/{id}/edit", name="game_edit")
     *
     */
    public function editAction(Game $game, Request $request, GameManager $gameManager)
    {

        $form = $this->createForm(GameType::class, $game);
        try {

            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $gameManager->save($game);
            }
        }
        catch(\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
        }
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
    public function manageAction(Game $game, PlayerManager $playerManager)
    {
        $card = new Card();
        $form = $this->createForm(CustomerGameType::class);
        $customersGame = $playerManager->getCustomersByGame($game);

        $form_card_customer = $this->createForm(CustomerAddCardType::class, $card);

        return $this->render('AppBundle:Game:manage.html.twig', array(
            'game' => $game,
            'form' => $form->createView(),
            'customersGame' => $customersGame,
            'form_card_customer' => $form_card_customer->createView()
        ));
    }

    /**
     * @param Request $request
     * @Route("/game/{id}/addUser", name="game_add_user")
     * @Method({"POST"})
     */
    public function addUserAction(Game $game, Request $request, ObjectManager $objectManager, PlayerManager $playerManager)
    {
        $customer_id = $request->request->get('customer_id');
        $customer = $objectManager->getRepository(Customer::class)->find($customer_id);
        $playerManager->add($customer, $game);
        return $this->redirectToRoute('game_manage', array('id' => $game->getId()));
    }

    /**
     * @param Request $request
     * @Route("/game/{id}/removeUser/{customer_id}", name="game_remove_user")
     * @Method({"POST"})
     */
    public function removeUserAction(Game $game, $customer_id, ObjectManager $objectManager, PlayerManager $playerManager)
    {
        $customer = $objectManager->getRepository(Customer::class)->find($customer_id);
        $playerManager->remove($customer, $game);
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
     * Ajax Method to search customer by card or nickname
     * @Route("/game/{id}/search", name="game_search_customer")
     * @Method({"GET", "POST"})
     */
    public function searchAction(Game $game, Request $request, GameManager $gameManager)
    {
        $params = $request->request->all();
        $response = $gameManager->searchCustomerWithGame($params, $game);
        return $this->json($response);
    }

    /**
     * AjaxMethod
     * @Route("/game/{id}/qrcode", name="game_qrcode")
     */
    public function qrcodeAction(Game $game, ObjectManager $objectManager, Request $request, PlayerManager $playerManager, CardManager $cardManager)
    {
        $return = ['status' => 1];
        try {
            $numero = $request->request->get('qrData');
            $card = $cardManager->search($numero);
            $playerManager->add($card->getCustomer(), $game);

        }
        catch(Exception $exception)
        {
            $return['status'] = 0;
            $this->get('session')->getFlashBag()->add('danger', $exception->getMessage());
        }

        return $this->json($return);
    }

}
