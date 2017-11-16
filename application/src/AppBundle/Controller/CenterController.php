<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CenterController extends Controller
{
    /**
     * @Route("/admin/center")
     */
    public function listAction()
    {
        return $this->render('AppBundle:Center:list.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/admin/center/create")
     */
    public function createAction()
    {
        return $this->render('AppBundle:Center:create.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/admin/center/{id}/edit")
     */
    public function editAction($id)
    {
        return $this->render('AppBundle:Center:edit.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/admin/center/{id}/delete")
     */
    public function deleteAction($id)
    {
        return $this->render('AppBundle:Center:delete.html.twig', array(
            // ...
        ));
    }

}
