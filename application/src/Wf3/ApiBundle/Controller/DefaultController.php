<?php

namespace Wf3\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        dump($_SERVER);
        return $this->render('ApiBundle:Default:index.html.twig');
    }
}
