<?php

namespace Wf3\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wf3\ApiBundle\Entity\Center;
use Wf3\ApiBundle\Entity\CenterRequest;
use Wf3\ApiBundle\Entity\ResponseRequest;
use Wf3\ApiBundle\Manager\ResponseRequestManager;

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
     * @Route("/cards", name="api_get_all")
     * @Method({"GET"})
     */
    public function getAllAction(Request $request)
    {
        //Recuperation du manager
        $responseManager = $this->get('api.manager.response');

        try {
            $responseGetAll = $responseManager->response($request, 'getAll');
        }
        catch (Exception $exception)
        {
            $responseGetAll = $responseManager->exception($exception->getMessage());
        }

        return $responseGetAll;
    }

    /**
     * Enregistre une requete de cards et retourne un reponse json
     * @Route("/request", name="api_create_request")
     * @Method({"POST"})
     * @return Response
     */
    public function requestAction(Request $request)
    {
        //Recuperation du manager
        $responseManager = $this->get('api.manager.response');

        try {
            //Doit contenir un tableau avec le n° du center
            $responseRequest = $responseManager->response($request, 'request');
        }
        catch (Exception $exception)
        {
            $responseRequest = $responseManager->exception($exception->getMessage());
        }

        return $responseRequest;
    }

}
