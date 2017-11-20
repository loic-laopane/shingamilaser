<?php

namespace AppBundle\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;

class PurchaseController extends Controller
{

    /**
     * @Route("/purchase/request")
     */
    public function requestAction()
    {
        $uri = 'http://localhost/web/app_dev.php/api/request';
        $serializer = $this->get('jms_serializer');
        $client = new Client();
        try {

            $response = $client->post($uri, array(
                'center' => 123,
                'quantity' => 0
            ));
        }
        catch (Exception $e)
        {

        }

        $data = $serializer->deserialize($response->getBody()->getContents(), 'array', 'json');

        return [
            'city' => $data['name'],
            'description' => $data['weather'][0]['main']
        ];
    }
        /*
        $res = $client->request('GET', 'http://localhost/web/app_dev.php/api/request');
        echo $res->getStatusCode(); // 200
        echo $res->getHeaderLine('content-type'); // 'application/json; charset=utf8'
        echo $res->getBody(); // '{"id": 1420053, "name": "guzzle", ...}'
*/
        return $this->render('AppBundle:Purchase:request.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/purchase/show")
     */
    public function showAction()
    {
        return $this->render('AppBundle:Purchase:show.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/purchase/list")
     */
    public function listAction()
    {
        return $this->render('AppBundle:Purchase:list.html.twig', array(
            // ...
        ));
    }

}
