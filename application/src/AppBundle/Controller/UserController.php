<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\User\UserEditType;
use AppBundle\Form\User\UserType;
use AppBundle\Manager\UserManager;
use AppBundle\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/admin/users/page/{page}", name="admin_user_list", defaults={"page" : 1})
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET"})
     */
    public function listAction(ObjectManager $objectManager, Request $request, $page)
    {
        $maxResult = $this->getParameter('max_result_page');
        $repository = $objectManager->getRepository(User::class);
        $users = $repository->getAllWithPage($page, $maxResult);
        $pagination = new Pagination($page, $repository->countAll(), $request->get('_route'), $maxResult);
        return $this->render('AppBundle:Admin:User/list.html.twig', array(
            'users' => $users,
            'pagination' => $pagination->getPagination()
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
        try {
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $userManager->insert($user);

                return $this->redirectToRoute('admin_user_edit', array(
                  'id' => $user->getId()
                ));

            }
        }
        catch (\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
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
     * @Method({"GET", "POST"})
     */
    public function deleteAction($id, UserManager $userManager)
    {
        try {
            $userManager->delete($id);
        }
        catch (\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->redirectToRoute('admin_user_list');
    }

}
