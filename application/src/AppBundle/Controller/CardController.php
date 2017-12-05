<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class CardController
 * @package AppBundle\Controller
 * @Route("/staff")
 */
class CardController extends Controller
{

    /**
     * @Route("/card/get", )
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
     * @Route("/cards/page/{page}", name="card_list", defaults={"page" : 1})
     */
    public function listAction(ObjectManager $objectManager, $page=1)
    {
        $maxDisplay = 10;
        $cards = $objectManager->getRepository(Card::class)->findAll();
        $cards =$objectManager->getRepository(Card::class)->getAll($page, $maxDisplay);
        $nbCard = $objectManager->getRepository(Card::class)->countAll();
        $pagination = array(
            'route' => 'card_list',
            'page' => $page,
            'page_count' => ceil($nbCard / $maxDisplay)
        );
        return $this->render('AppBundle:Card:list.html.twig', array(
            'cards' => $cards,
            'pagination' => $pagination

        ));
    }

}
