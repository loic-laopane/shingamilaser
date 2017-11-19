<?php

namespace Wf3\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wf3\ApiBundle\Entity\Center;
use Wf3\ApiBundle\Entity\CenterRequest;
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
    public function getAllAction()
    {
        return $this->json([]);
    }

    /**
     * Enregistre une requete de cards
     * @Route("/request", name="api_create_request")
     * @Method({"POST"})
     */
    public function requestAction(Request $request)
    {

        $responseRequestManager = $this->get('api.manager.response_request');
        $serializer = $this->get('jms_serializer');

        //Doit contenir un tableau avec le n° du center
        $data = $serializer->deserialize($request->getContent(), 'array', 'json');

        //On cherche le center en base
        //On retourne une reponse
        $responseRequest = $responseRequestManager->reponse($data);

        $returnedResponse = $serializer->serialize($responseRequest, 'json');

        $response = new Response($returnedResponse);
        $request->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
