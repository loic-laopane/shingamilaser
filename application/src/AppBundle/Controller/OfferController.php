<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class OfferController extends Controller
{
    /**
     * @Route("/")
     */
    public function listAction()
    {
        return $this->render('AppBundle:Offer:list.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/create")
     */
    public function createAction()
    {
        return $this->render('AppBundle:Offer:create.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction($id)
    {
        return $this->render('AppBundle:Offer:edit.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction($id)
    {
        return $this->render('AppBundle:Offer:delete.html.twig', array(
            // ...
        ));
    }

}
