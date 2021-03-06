<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\CustomerOffer;
use AppBundle\Form\Customer\CustomerAccountType;
use AppBundle\Form\Customer\CustomerAddCardType;
use AppBundle\Form\User\UserAccountType;
use AppBundle\Manager\CardManager;
use AppBundle\Manager\CustomerManager;
use AppBundle\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{
    /**
     * @param $objectManager ObjectManager
     * @param $customerManager CustomerManager
     * @Route("/account/show", name="account_show")
     * @Method({"GET"})
     */
    public function showAction(ObjectManager $objectManager, CustomerManager $customerManager)
    {
        $customer = $customerManager->getCustomerByUser($this->getUser());
        $customerOffers = $objectManager->getRepository(CustomerOffer::class)->findCustomerOffers($customer);
        $cards = $customerManager->getCustomerCards($customer);
        return $this->render('AppBundle:Account:show.html.twig', array(
            'customer' => $customer,
            'customerOffers' => $customerOffers,
            'cards' => $cards
        ));
    }

    /**
     * @param $request Request
     * @param $userManager UserManager
     * @Route("/account/edit", name="account_edit")
     */
    public function editUserAction(Request $request, UserManager $userManager)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserAccountType::class, $user);
        $plainPaswword = $request->request->get('password');
        try {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if (!empty($plainPaswword)) {
                    $user->setPassword($plainPaswword);
                    $userManager->encodeUserPassword($user);
                }
                $userManager->save($user);
                $this->addFlash('success', 'Account updated');
            }
        } catch (\Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->render('AppBundle:Account:edit_user.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * @param $request Request
     * @param $manager CustomerManager
     * @Route("/account/profile/edit", name="account_profile_edit")
     */
    public function editCustomerAction(Request $request, CustomerManager $manager)
    {
        $customer = $manager->getCustomerByUser($this->getUser());

        $form = $this->createForm(CustomerAccountType::class, $customer);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager->save($customer);
                $this->addFlash('success', 'Profile updated');
            }
        } catch (\Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->render('AppBundle:Account:edit_customer.html.twig', array(
            'form' => $form->createView(),
            'customer' => $customer
        ));
    }

    /**
     * toDo : Method a supprimer apres verification
     * @param $request Request
     * @param $customerManager CustomerManager
     * @param $cardManager CardManager
     * @Route("/account/cards/add", name="account_card_add")
     */
    public function addCardAction(Request $request, CustomerManager $customerManager, CardManager $cardManager)
    {
        $customer = $customerManager->getCustomerByUser($this->getUser());
        $empty_card = new Card();
        $form = $this->createForm(CustomerAddCardType::class, $empty_card);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $cardManager->rattach($empty_card->getNumero(), $customer);
            }
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }
        return $this->render('AppBundle:Account:add_card.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Card $card
     * @param ObjectManager $objectManager
     * @throws \Exception
     * @Route("/account/card/{numero}", name="account_card_show")
     */
    public function showCardAction(Card $card, CardManager $cardManager)
    {
        try {
            $cardManager->checkUser($card, $this->getUser());
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'alert.not_your_card');
            return $this->redirectToRoute('account_show');
        }


        return $this->render('AppBundle:Account:show_card.html.twig', array(
            'card' => $card
        ));
    }

    /**
     * @param $customerManager CustomerManager
     * @Route("/account/offers", name="account_offers_show")
     */
    public function showOffersAction(CustomerManager $customerManager)
    {
        $customer = $customerManager->getCustomerByUser($this->getUser());
        $offers = $this->getDoctrine()->getRepository(CustomerOffer::class)->findCustomerOffers($customer);

        return $this->render('AppBundle:Account:show_offers.html.twig', array(
            'offers' => $offers
        ));
    }

    /**
     * @Route("/customer/{id}/removeAvatar", name="customer_avatar_remove")
     * @Method({"POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function removeAvatar(Customer $customer, CustomerManager $customerManager)
    {
        $response = array('status' => 1);
        try {
            $customerManager->removeAvatar($customer);
        } catch (Exception $e) {
            $response['error'] = 'Error while removing avatar';
            $response['status'] = 0;
        }

        return $this->json($response);
    }
}
