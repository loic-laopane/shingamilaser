<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Purchase;
use AppBundle\Form\PurchaseType;
use AppBundle\Manager\PurchaseManager;
use Doctrine\Common\Persistence\ObjectManager;
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
 * @Route("/staff")
 */
class PurchaseController extends Controller
{

     /**
     * @param Request $request
     * @return Response
     * @Route("/purchase/create", name="purchase_create")
     * @Security("has_role('ROLE_STAFF')")
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
            $this->get('session')->getFlashBag()->add('success', 'Purchase updated');
        }

        return $this->render('AppBundle:Purchase:edit.html.twig', array(
            'form' => $form->createView(),
            'purchase' => $purchase
        ));
    }
    /**
     * @Route("/purchase/show", name="purchase_show")
     */
    public function showAction()
    {
        return $this->render('AppBundle:Purchase:show.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/purchase/list", name="purchase_list")
     */
    public function listAction(ObjectManager $objectManager)
    {
        $purchases = $objectManager->getRepository(Purchase::class)->getAll($this->getUser());
        return $this->render('AppBundle:Purchase:list.html.twig', array(
            'purchases' => $purchases
        ));
    }

}
