<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CustomerGame;
use AppBundle\Entity\Game;
use AppBundle\Form\CustomerGameType;
use AppBundle\Form\GameType;
use AppBundle\Manager\GameManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class GameController extends Controller
{
    /**
     * @Route("/manage/games", name="game_list")
     */
    public function listAction()
    {
        return $this->render('AppBundle:Game:list.html.twig', array(
            // ...
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
     */
    public function editAction(Game $game, Request $request)
    {
        $customerGame = new CustomerGame();
        $customerGame->setGame($game);
        $form = $this->createForm(CustomerGameType::class, $customerGame);
        $form->handleRequest($request);
        return $this->render('AppBundle:Game:edit.html.twig', array(
            'form' => $form->createView(),
            'game' => $game
        ));
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
     */
    public function searchAction($id)
    {
        return $this->render('AppBundle:Game:delete.html.twig', array(
            // ...
        ));
    }
}
