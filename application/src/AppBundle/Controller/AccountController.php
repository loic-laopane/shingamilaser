<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\CustomerOffer;
use AppBundle\Entity\User;
use AppBundle\Form\CustomerAccountType;
use AppBundle\Form\CustomerAddCardType;
use AppBundle\Form\UserAccountType;
use AppBundle\Manager\CardManager;
use AppBundle\Manager\CustomerManager;
use AppBundle\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function editUserAction(Request $request, ObjectManager $objectManager)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserAccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $objectManager->flush();
            $this->addFlash('success', 'Account updated');
        }
        return $this->render('AppBundle:Account:edit_user.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * @Route("/account/profile/edit", name="account_profile_edit")
     */
    public function editCustomerAction(Request $request, CustomerManager $manager)
    {

        $customer = $manager->getCustomerByUser($this->getUser());

        $form = $this->createForm(CustomerAccountType::class, $customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->save($customer);
            $this->addFlash('success', 'Profile updated');
        }
        return $this->render('AppBundle:Account:edit_customer.html.twig', array(
            'form' => $form->createView(),
            'customer' => $customer
        ));
    }

    /**
     * @Route("/account/cards/add", name="account_card_add")
     */
    public function addCardAction(Request $request, CustomerManager $customerManager, CardManager $cardManager)
    {

        $customer = $customerManager->getCustomerByUser($this->getUser());
        $empty_card = new Card();
        $form = $this->createForm(CustomerAddCardType::class, $empty_card);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $cardManager->rattach($empty_card->getNumero(), $customer);
        }
        return $this->render('AppBundle:Account:add_card.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/account/offers", name="account_offers_show")
     */
    public function showOffersAction(ObjectManager $objectManager, CustomerManager $customerManager)
    {
        /*
        $customer = $customerManager->getCustomerByUser($this->getUser());
        $offers = $this->getDoctrine()->getRepository(CustomerOffer::class)->findByCustomer($customer);
        */
        return $this->render('AppBundle:Account:show_offers.html.twig', array(

        ));
    }

}
