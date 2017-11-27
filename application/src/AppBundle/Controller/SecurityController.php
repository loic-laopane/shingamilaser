<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerRegisterType;
use AppBundle\Manager\CustomerManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $errors = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'errors_login' => $errors
        ));
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, CustomerManager $customerManager)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerRegisterType::class, $customer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try {
                $customerManager->register($customer);
                //Todo : A ajouter a un evenement
                $this->addFlash('success', 'registration.success');
                return $this->redirectToRoute('login');
            }
            catch(\Exception $exception)
            {
                $this->addFlash('danger', $exception->getMessage());
            }


        }

        return $this->render('AppBundle:Security:register.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin", name="admin")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminAction()
    {
        return $this->render('AppBundle:Security:admin.html.twig', array(

        ));
    }

}
