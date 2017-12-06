<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function listAction(ObjectManager $objectManager, Request $request, $page)
    {
        $maxResult = $this->getParameter('max_result_page');
        $repository = $objectManager->getRepository(Card::class);
        $cards = $repository->getAllWithPage($page, $maxResult);
        $pagination = new Pagination($page, $repository->countAll(), $request->get('_route'), $maxResult);

        return $this->render('AppBundle:Card:list.html.twig', array(
            'cards' => $cards,
            'pagination' => $pagination->getPagination()

        ));
    }
}
