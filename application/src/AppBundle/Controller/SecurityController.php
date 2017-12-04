<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;

use AppBundle\Form\Customer\CustomerRegisterType;
use AppBundle\Form\Password\NewPasswordType;
use AppBundle\Form\Password\RequestPasswordType;
use AppBundle\Manager\CustomerManager;
use AppBundle\Manager\RequestPasswordManager;
use AppBundle\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Method({"GET", "POST"})
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
     * @Method({"GET", "POST"})
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
     * @Route("/password/forgotten", name="forgotten_password")
     * @Method({"GET", "POST"})
     */
    public function forgottenPasswordAction(Request $request, RequestPasswordManager $requestPasswordManager, UserManager $userManager)
    {
        $form = $this->createForm(RequestPasswordType::class);
        try {
            if ($request->isMethod('POST'))
            {
                $email = $request->request->get('email');
                $user = $userManager->getUserByEmail($email);
                $requestPasswordManager->create($user);
                $this->addFlash('success', 'A email has been sent to '.$email);
            }

        }
        catch(\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->render('AppBundle:Security:forgotten.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/password/change/{token}", name="new_password")
     * @Method({"GET", "POST"})
     */
    public function newPasswordAction($token, Request $request, RequestPasswordManager $requestPasswordManager, UserManager $userManager)
    {

        try {
            $user = $requestPasswordManager->getUserByToken($token);
            $form = $this->createForm(NewPasswordType::class, $user);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $userManager->changePassword($user);
                $this->addFlash('success', 'Password updated');
                return $this->redirectToRoute('login');
            }
            return $this->render('AppBundle:Security:new-password.html.twig', array(
                'form' => $form->createView()
            ));
        }
        catch(\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
            return $this->redirectToRoute('forgotten_password');
        }

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
