<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Center;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApiCardController extends Controller
{
    /**
     * @Route("/api/list")
     */
    public function listAction()
    {
        return $this->render('AppBundle:ApiCard:list.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/api/get", name="cards_get")
     * @Method({"POST"})
     */
    public function getAction(Request $request, ObjectManager $objectManager)
    {
        //$data = $request->getContent();
        $serializer = $this->get('jms_serializer');
        $center = new Center();
        $center->setCode(123);
        $card = new Card();
        $card->setCode(142121)->setCenter($center);
        $list = [$card, $card];
        $data = $serializer->serialize($list, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("api/request")
     * @Method({"POST"})
     */
    public function requestAction(Request $request, SerializerInterface $serializer)
    {
        $data = $request->getContent();
        //$centerRequest = $serializer->deserialize($data, 'AppBundle\Entity\CenterRequest', 'json');
        //print_r($centerRequest);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
