<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class CardController
 * @package AppBundle\Controller
 * @Route("/card")
 */
class CardController extends Controller
{

    /**
     * @Route("/get", )
     */
    public function getAction()
    {
        return $this->render('AppBundle:Card:get.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/card/search")
     */
    public function searchAction()
    {
        return $this->render('AppBundle:Card:search.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/cards", name="card_list")
     */
    public function listAction(ObjectManager $objectManager)
    {
        $cards = $objectManager->getRepository(Card::class)->findAll();
        return $this->render('AppBundle:Card:list.html.twig', array(
            'cards' => $cards
        ));
    }

}
