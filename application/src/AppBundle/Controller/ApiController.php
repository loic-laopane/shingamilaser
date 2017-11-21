<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Purchase;
use AppBundle\Form\PurchaseType;
use AppBundle\Manager\PurchaseManager;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PurchaseController
 * @package AppBundle\Controller
 * @Route("/client")
 */
class ApiController extends Controller
{
    /**
     * @Route("/purchase/{id}/request", name="api_client_request")
     * @Method({"POST"})
     */
    public function requestAction(Purchase $purchase, PurchaseManager $purchaseManager)
    {
        try {

            $purchaseManager->makeRequest($purchase);
        }
        catch (Exception $e)
        {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirectToRoute('purchase_edit', array('id' => $purchase->getId()));
    }

    /**
     * @Route("/purchase/{id}/getall", name="api_client_getall")
     * @Method({"POST"})
     */
    public function getAllAction(Purchase $purchase, PurchaseManager $purchaseManager)
    {
        try {

            $purchaseManager->getCards($purchase);
        }
        catch (Exception $e)
        {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirectToRoute('purchase_edit', array('id' => $purchase->getId()));
    }

}
