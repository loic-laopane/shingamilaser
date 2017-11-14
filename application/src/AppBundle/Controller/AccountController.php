<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AccountController extends Controller
{
    /**
     * @Route("/account/show", name="account_show")
     */
    public function showAction()
    {
        return $this->render('AppBundle:Account:show.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/account/edit", name="account_edit")
     */
    public function editUserAction()
    {
        return $this->render('AppBundle:Account:edit_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/account/profile/edit", name="account_profile_edit")
     */
    public function editCustomerAction()
    {
        return $this->render('AppBundle:Account:edit_customer.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/account/cards/add", name="account_card_add")
     */
    public function addCardAction()
    {
        return $this->render('AppBundle:Account:add_card.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/account/offers", name="account_offers_show")
     */
    public function showOffersAction()
    {
        return $this->render('AppBundle:Account:show_offers.html.twig', array(
            // ...
        ));
    }

}
