<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Purchase;
use AppBundle\Form\PurchaseType;
use AppBundle\Manager\PurchaseManager;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PurchaseController
 * @package AppBundle\Controller
 * @Route("/staff")
 */
class PurchaseController extends Controller
{

    /**
     * @Route("/purchase/{id}/request", name="purchase_request")
     */
    public function requestAction(Request $request, Purchase $purchase, PurchaseManager $purchaseManager)
    {
        $base_uri = 'http://localhost/web/app_dev.php/api/';
        $serializer = $this->get('jms_serializer');
        $client = new Client(['base_uri' => $base_uri]);

        try {

            $data = ['center' => 123, 'quantity' => $purchase->getQuantity()];
            $jsonData = $serializer->serialize($data, 'json');
            $response = $client->request('POST', 'request', array(
                'body' => $jsonData
            ));

            //print_r($response);
            $purchaseManager->thread($purchase, $response);

            //$data = $serializer->deserialize($response->getBody()->getContents(), 'array', 'json');

        }

        catch (Exception $e)
        {
            dump($e->getMessage());
        }



        return $this->redirectToRoute('purchase_edit', array('id' => $purchase->getId()));

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
     * @param Request $request
     * @return Response
     * @Route("/purchase/create", name="purchase_create")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createAction(Request $request, PurchaseManager $purchaseManager)
    {
        $purchase = new Purchase();
        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);
        $purchase->setRequester($this->getUser());
        if ($form->isSubmitted() && $form->isValid())
        {
            $purchaseManager->create($purchase);
            return $this->redirectToRoute('purchase_edit', array(
                'id' => $purchase->getId()
            ));
        }

        return $this->render('AppBundle:Purchase:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Purchase $purchase
     * @Route("/purchase/{id}/edit", name="purchase_edit")
     */
    public function editAction(Request $request, Purchase $purchase, PurchaseManager $purchaseManager)
    {
        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $purchaseManager->save($purchase);
        }

        return $this->render('AppBundle:Purchase:edit.html.twig', array(
            'form' => $form->createView(),
            'purchase' => $purchase
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
