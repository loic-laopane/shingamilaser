<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Center;
use AppBundle\Form\CenterType;
use AppBundle\Manager\CenterManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class CenterController extends Controller
{
    /**
     * @Route("/admin/centers", name="admin_center_list")
     * @Security("has_role('ROLE_SUPERADMIN')")
     * @Method({"GET"})
     */
    public function listAction(ObjectManager $objectManager)
    {
        $centers = $objectManager->getRepository(Center::class)->findAll();
        return $this->render('AppBundle:Admin:Center/list.html.twig', array(
            'centers' => $centers
        ));
    }

    /**
     * @Route("/admin/center/create", name="admin_center_create")
     * @Security("has_role('ROLE_SUPERADMIN')")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request, CenterManager $centerManager)
    {
        $center = new Center();
        $form = $this->createForm(CenterType::class, $center);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if($centerManager->insert($center)) {

                return $this->redirectToRoute('admin_center_edit', array(
                    'id' => $center->getId()
                ));
            }

        }
        return $this->render('AppBundle:Admin:Center/store.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/center/{id}/edit", name="admin_center_edit")
     * @Security("has_role('ROLE_SUPERADMIN')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Center $center, ObjectManager $objectManager)
    {
        $form = $this->createForm(CenterType::class, $center);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //Flush
            $objectManager->flush();
            $this->addFlash('success', 'Center updated');
        }
        return $this->render('AppBundle:Admin:Center/store.html.twig', array(
            'form' => $form->createView(),
            'center' => $center
        ));
    }

    /**
     * @Route("/admin/center/{id}/delete", name="admin_center_delete")
     * @Security("has_role('ROLE_SUPERADMIN')")
     * @Method({"GET"})
     */
    public function deleteAction($id, CenterManager $centerManager)
    {
        $centerManager->delete($id);
        return $this->redirectToRoute('admin_center_list');

    }

}
