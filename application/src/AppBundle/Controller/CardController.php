<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CardController extends Controller
{

    /**
     * @Route("card/get")
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
     * @Route("/cards")
     */
    public function listAction()
    {
        return $this->render('AppBundle:Card:list.html.twig', array(
            // ...
        ));
    }

}
