<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use AppBundle\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/admin/users", name="admin_user_list")
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
     */
    public function createAction(Request $request, ObjectManager $objectManager)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $objectManager->persist($user);
            $objectManager->flush();
            $this->addFlash('success', 'User created');
            return $this->redirectToRoute('admin_user_edit', array(
                'id' => $user->getId()
            ));
        }

        return $this->render('AppBundle:Admin:User/store.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
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
     */
    public function deleteAction($id, ObjectManager $objectManager)
    {
        $user = $objectManager->getRepository(User::class)->find($id);
        if (null === $user){
           $this->addFlash('danger', 'User doesn\'t exist');
        }
        else {
            $username = $user->getUsername();
            $objectManager->remove($user);
            $objectManager->flush();
            $this->addFlash('success', 'User '.$username.' has been deleted');
        }
        return $this->redirectToRoute('admin_user_list');
    }

}
