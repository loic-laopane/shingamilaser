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
        $responseRequestManager = $this->get('api.manager.response_request');

        //Recuperation du serializer
        $serializer = $this->get('jms_serializer');

        try {
            //Doit contenir un tableau avec le n° du center
            $data = $serializer->deserialize($request->getContent(), 'array', 'json');
            //On retourne une ResponseRequest
            $responseRequest = $responseRequestManager->response($data);
        }
        catch (Exception $exception)
        {
            $responseRequest = $responseRequestManager->exception($exception->getMessage());
        }

        //Formattage de l'objet ResponseRequest en JSON
        $jsonResponse = $serializer->serialize($responseRequest, 'json');

        //Creation et envoi d'une reponse
        $response = new Response();
        $response->setContent($jsonResponse);
        $request->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Enregistre une requete de cards
     * @Route("/request", name="api_create_request")
     * @Method({"POST"})
     */
    public function requestAction(Request $request)
    {
        //Recuperation du manager
        $responseRequestManager = $this->get('api.manager.response_request');

        //Recuperation du serializer
        $serializer = $this->get('jms_serializer');


        try {
            //Doit contenir un tableau avec le n° du center
            $data = $serializer->deserialize($request->getContent(), 'array', 'json');
            //On retourne une ResponseRequest
            $responseRequest = $responseRequestManager->response($data, 'request');
        }
        catch (Exception $exception)
        {
            $responseRequest = $responseRequestManager->exception($exception->getMessage());
        }

        //Formattage de l'objet ResponseRequest en JSON
        $jsonResponse = $serializer->serialize($responseRequest, 'json');

        //Creation et envoi d'une reponse
        $response = new Response();
        $response->setContent($jsonResponse);
        $request->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
