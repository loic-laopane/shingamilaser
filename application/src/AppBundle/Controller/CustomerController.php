<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use AppBundle\Form\CustomerAddCardType;
use AppBundle\Form\CustomerQuickCreateType;
use AppBundle\Manager\CardManager;
use AppBundle\Manager\CustomerManager;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CustomerController
 * @package AppBundle\Controller
 * @Route("/customer")
 */
class CustomerController extends Controller
{
    /**
     * @Route("/search", name="customer_search")
     * @Security("has_role('ROLE_STAFF', 'ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function searchAction(Request $request, ObjectManager $objectManager)
    {
        $term = $request->request->get('term');
        $customers = $objectManager->getRepository(Customer::class)->findByNicknameLike($term);
        $return = [];
        foreach($customers as $customer)
        {
            $return[] = $customer->getNickname();
        }
        return $this->json($return);
    }

    /**
     * @param Customer $customer
     * @Route("/customer/{id}/modal", name="customer_modal")
     */
    public function displayModalAddCardToCustomerAction(Customer $customer)
    {
        return $this->render('AppBundle:Customer:modal_add_card.html.twig', array(
            'customer' => $customer
        ));
    }

    /**
     * Affiche le formulaire de la modale
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/customer/{id}/renderForm", name="customer_card_form")
     */
    public function renderCustomerCardTypeAction(Customer $customer)
    {
        $empty_card = new Card();
        $form = $this->createForm(CustomerAddCardType::class, $empty_card, array(
            'attr' => array('id' => 'form_add_card'),
            'action' => $this->generateUrl('customer_associate_card', array('id' => $customer->getId())),
            'method' => 'POST'
        ));

        return $this->render('AppBundle:Customer:link_card_form.html.twig', array(
            'form' => $form->createView(),
            'customer' => $customer
        ));
    }

    /**
     * @param Request $request
     * Associe un carte a un customer en ajax
     * @Route("customer/{id}/addCard", name="customer_associate_card")
     * @Method({"POST"})
     */
    public function associateCard(Request $request, Customer $customer, CardManager $cardManager)
    {
        $numero = $request->request->get('numero');
        $card = new Card();
        $response = ['status' => 0, 'message' => ''];

        try {
            $card->setNumero($numero);
            $cardManager->rattach($card->getNumero(), $customer);
            $response['status'] = 1;
            $response['message'] = 'Card associated to '.($customer->getUser() === $this->getUser() ? 'your account' : ' customer '.$customer->getNickname());
            $response['card'] = $card;
        }
        catch (\Exception $e)
        {
            //$this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            $response['message'] = $e->getMessage();
        }
        return $this->json($response);
    }

    /**
     * @param Request $request
     * @Route("/customer/getCards", name="customer_get_cards")
     */
    public function getCardsAction(Request $request, ObjectManager $objectManager) {
        $customer = $objectManager->getRepository(Customer::class)->findOneBy(['user' => $this->getUser()]);
        return $this->render('AppBundle:Account:customer_cards.html.twig', array('customer' => $customer));
    }

    /**
     * @param Customer $customer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/customer/create/quick", name="customer_create_quick")
     */
    public function renderCreateQuickCustomerTypeAction(Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerQuickCreateType::class, $customer, array(
            'attr' => array('id' => 'form_customer_quick_create'),
            'action' => $this->generateUrl('customer_create'),
            'method' => 'POST'
        ));

        return $this->render('AppBundle:Customer:create_form.html.twig', array(
            'form' => $form->createView(),
            'customer' => $customer
        ));
    }

    /**
     * @param Request $request
     * Associe un carte a un customer en ajax
     * @Route("/customer/create/quick/new", name="customer_create")
     * @Method({"POST"})
     */
    public function createCustomer(Request $request, CustomerManager $customerManager)
    {
        $nickname = $request->request->get('nickname');
        $email = $request->request->get('email');


        $response = ['status' => 0, 'message' => ''];

        try {
            $customer = new Customer();
            $customer->setNickname($nickname);
dump($customer);
            $user = new User();
            $user->setEmail($email);
            $customerManager->quickCreate($customer, $user);
            $response['status'] = 1;
            $response['message'] = 'Account created';
        }
        catch (\Exception $e)
        {
            //$this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            $response['message'] = $e->getMessage();
        }
        return $this->json($response);
    }

}
