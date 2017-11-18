<?php

namespace Wf3\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Wf3\ApiBundle\Entity\CenterRequest;

class ServiceController extends Controller
{
    /**
     * Fournit toutes les cards créées
     * @Route("/list", name="api_list")
     * @Method({"GET"})
     */
    public function listAction()
    {
        return $this->json([]);
    }

    /**
     * Fournit toutes les cards d'une requete
     * @Route("/cards/{id}", name="api_get_all")
     * @Method({"GET"})
     */
    public function getAllAction(Request $request, CenterRequest $centerRequest)
    {
        return $this->json([]);
    }

    /**
     * Enregistre une requete de cards
     * @Route("/request/{code_center}", name="api_create_request")
     * @Method({"POST"})
     */
    public function requestAction(Request $request, $code_center)
    {
        return $this->json([]);
    }

}
