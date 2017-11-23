<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use AppBundle\Form\UserType;
use AppBundle\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/admin/users", name="admin_user_list")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET"})
     */
    public function listAction(ObjectManager $objectManager)
    {
        $users = $objectManager->getRepository(User::class)->findAll();
        return $this->render('AppBundle:Admin:User/list.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * @Route("/admin/user/create", name="admin_user_create")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET","POST"})
     */
    public function createAction(Request $request, UserManager $userManager)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if($userManager->insert($user))
            {
                return $this->redirectToRoute('admin_user_edit', array(
                    'id' => $user->getId()
                ));
            }
        }

        return $this->render('AppBundle:Admin:User/store.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user, ObjectManager $objectManager)
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //Flush
            $objectManager->flush();
            $this->addFlash('success', 'User updated');
        }
        return $this->render('AppBundle:Admin:User/store.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET"})
     */
    public function deleteAction($id, UserManager $userManager)
    {
        $userManager->delete($id);
        return $this->redirectToRoute('admin_user_list');
    }

}
