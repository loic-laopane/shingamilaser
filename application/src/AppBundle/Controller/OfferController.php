<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offer;
use AppBundle\Form\Offer\OfferType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OfferController
 * @package AppBundle\Controller
 * @Route("/offers")
 */
class OfferController extends Controller
{
    /**
     * @Route("/", name="offer_list")
     * @Method({"GET"})
     * @Security("has_role('ROLE_STAFF')")
     */
    public function listAction(ObjectManager $objectManager)
    {
        $offers = $objectManager->getRepository('AppBundle:Offer')->findAll();
        return $this->render('AppBundle:Offer:list.html.twig', array(
            'offers' => $offers
        ));
    }

    /**
     * @Route("/create", name="offer_create")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_STAFF')")
     */
    public function createAction(Request $request)
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try
            {
                //save offer
                $this->addFlash('success', 'Offer created');
                return $this->redirectToRoute('offer_edit', array('id' => $offer->getId()));
            }
            catch (\Exception $exception)
            {
                $this->addFlash('danger', $exception->getMessage());
            }

        }
        return $this->render('AppBundle:Offer:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit", name="offer_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_STAFF')")
     */
    public function editAction(Offer $offer, Request $request)
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try {
                //save
                $this->addFlash('success', 'Offer '.$offer->getTitle().' update');
            }
            catch (\Exception $exception)
            {
                $this->addFlash('danger', $exception->getMessage());
            }

        }

        return $this->render('AppBundle:Offer:edit.html.twig', array(
            'form' => $form->createView(),
            'offer' => $offer
        ));
    }

}
