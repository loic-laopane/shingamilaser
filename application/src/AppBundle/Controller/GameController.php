<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Game;
use AppBundle\Form\CustomerGameType;
use AppBundle\Form\GameType;
use AppBundle\Manager\CardManager;
use AppBundle\Manager\CustomerGameManager;
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
        $form = $this->createForm(CustomerGameType::class);
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
    public function addUserAction(Game $game, Request $request, ObjectManager $objectManager, CustomerGameManager $customerGameManager)
    {
        $customer_id = $request->request->get('customer_id');
        $customer = $objectManager->getRepository(Customer::class)->find($customer_id);
        $customerGameManager->add($customer, $game);
        return $this->redirectToRoute('game_manage', array('id' => $game->getId()));
    }

    /**
     * @param Request $request
     * @Route("/game/{id}/removeUser/{customer_id}", name="game_remove_user")
     * @Method({"POST"})
     */
    public function removeUserAction(Game $game,  $customer_id, ObjectManager $objectManager, CustomerGameManager $customerGameManager)
    {
        $customer = $objectManager->getRepository(Customer::class)->find($customer_id);
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
     * @Route("/game/{id}/search", name="game_search_customer")
     * @Method({"GET", "POST"})
     */
    public function searchAction(Game $game, Request $request, CustomerManager $customerManager)
    {
        $params = $request->request->all();
        $numero = $request->request->get('numero');
        $nickname = $request->request->get('nickname');
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
            $customers = $customerManager->getCustomerByParams($params);
            if(count($customers) == 0) {
                $response['message'] = 'No customer found';
            }
            else {
                foreach($customers  as $customer)
                {
                    $response['data'] .= $this->renderView('AppBundle:Game:customer.html.twig', array(
                        'customer' => $customer,
                        'game' => $game
                    ));
                }
                $response['status'] = 1;
                $response['message'] = 'Customer found';

            }
        }





        return $this->json($response);
    }

    /**
     * @Route("/qrcode", name="game_qrcode")
     */
    public function qrcodeAction(ObjectManager $objectManager, Request $request, CustomerGameManager $customerGameManager, CardManager $cardManager)
    {
        $return = ['status' => 1];
        try {
            $numero = $request->request->get('qrData');
            $game_id = $request->request->get('game_id');
            $game = $objectManager->getRepository('AppBundle:Game')->find($game_id);
            $card = $cardManager->search($numero);
            $customerGameManager->add($card->getCustomer(), $game);


        }
        catch(Exception $exception)
        {
            $return['status'] = 0;
            $this->get('session')->getFlashBag()->add('danger', $exception->getMessage());
        }

        return $this->json($return);
    }
}
