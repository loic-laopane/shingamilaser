<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerAddCardType;
use AppBundle\Manager\CardManager;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * Affiche le formulaire de la modale
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/customer/{id}/renderForm", name="customer_associate_card_form")
     */
    public function renderAssociateCardTypeAction(Customer $customer)
    {
        $empty_card = new Card();
        $form = $this->createForm(CustomerAddCardType::class, $empty_card, array(
            'attr' => array('id' => 'form_add_card'),
            'action' => $this->generateUrl('customer_associate_card', array('id' => $customer->getId())),
            'method' => 'POST'
        ));

        return $this->render('AppBundle:Customer:associated_card.html.twig', array(
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
        }
        catch (\Exception $e)
        {
            //$this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            $response['message'] = $e->getMessage();
        }
        return $this->json($response);
    }

}
