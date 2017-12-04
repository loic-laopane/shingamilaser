<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use AppBundle\Form\Customer\CustomerAccountType;
use AppBundle\Form\Customer\CustomerAddCardType;
use AppBundle\Form\Customer\CustomerGameType;
use AppBundle\Form\Customer\CustomerQuickCreateType;
use AppBundle\Form\Customer\CustomerRegisterType;
use AppBundle\Form\Customer\CustomerSearchType;
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

 */
class CustomerController extends Controller
{
    /**
     * Requete pour autocomplete le nickname
     * @Route("/customer/search", name="ajax_customer_search")
     * @Method({"GET", "POST"})
     */
    public function ajaxSearchAction(Request $request, ObjectManager $objectManager)
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
            'attr' => array('id' => 'form_add_card'.$customer->getId()),
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
     * @Route("/customer/{id}/addCard", name="customer_associate_card")
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
            $user = new User();
            $user->setEmail($email);
            $customerManager->quickCreate($customer, $user);
            $response['status'] = 1;
            $response['message'] = 'Account created';
        }
        catch (\Exception $e)
        {
            $response['message'] = $e->getMessage();
        }
        return $this->json($response);
    }


    /**
     * @Route("/staff/customer/create", name="staff_customer_create")
     * @Security("has_role('ROLE_STAFF')")
     */
    public function createAction(Request $request, CustomerManager $customerManager)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerRegisterType::class, $customer);
        try {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $customerManager->register($customer);
                $this->addFlash('success', 'Customer has been created');
                $this->redirectToRoute('staff_customer_edit', $customer->getId());
            }
        } catch (\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->render('AppBundle:Customer:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/staff/customer/{id}/edit", name="staff_customer_edit")
     * @Security("has_role('ROLE_STAFF')")
     */
    public function editAction(Customer $customer, Request $request, CustomerManager $customerManager)
    {
        $form = $this->createForm(CustomerAccountType::class, $customer);
        try {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $customerManager->save($customer);
                $this->addFlash('success', 'Customer has been updated');
            }
        } catch (\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->render('AppBundle:Customer:edit.html.twig', array(
            'form' => $form->createView(),
            'customer' => $customer
        ));
    }

    /**
     * Form de cherche de Customer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("staff/customer/search", name="staff_customer_search")
     */
    public function searchAction(Request $request, CustomerManager $customerManager)
    {
        $customers = [];
        $search_fields = ['numero' => '', 'nickname' => '', 'firstname' => '', 'lastname' => ''];
        $form = $this->createForm(CustomerSearchType::class);
        try {
            //$form->handleRequest($request);

            if ($request->isMethod('POST'))
            {

                $search_fields = $request->request->all();
                $customerManager->checkSearchParams($search_fields);
                $customers = $customerManager->getCustomerByParams($search_fields);
            }
        } catch (\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->render('AppBundle:Customer:search.html.twig', array(
            'form' => $form->createView(),
            'customers' => $customers,
            'search' => $search_fields
        ));
    }
}
