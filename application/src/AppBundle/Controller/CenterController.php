<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Center;
use AppBundle\Form\CenterType;
use AppBundle\Manager\CenterManager;
use AppBundle\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class CenterController extends Controller
{
    /**
     * @Route("/admin/centers/page/{page}", name="admin_center_list", defaults={"page" : 1})
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET"})
     */
    public function listAction(ObjectManager $objectManager, Request $request, $page)
    {
        $maxResult = $this->getParameter('max_result_page');
        $repository = $objectManager->getRepository(Center::class);
        $centers = $repository->getAllWithPage($page, $maxResult);
        $pagination = new Pagination($page, $repository->countAll(), $request->get('_route'), $maxResult);

        return $this->render('AppBundle:Admin:Center/list.html.twig', array(
            'centers' => $centers,
            'pagination' => $pagination->getPagination()
        ));
    }

    /**
     * @Route("/admin/center/create", name="admin_center_create")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request, CenterManager $centerManager, TranslatorInterface $translator)
    {
        $center = new Center();
        $form = $this->createForm(CenterType::class, $center);

        try{
            $form->handleRequest($request);

            $em_api = $this->getDoctrine()->getManager('api');
            $center_api = new \Wf3\ApiBundle\Entity\Center();
            $center_api->setName($center->getName())
                ->setCode($center->getCode())
                ->setAddress($center->getAddress())
                ->setZipcode($center->getZipcode())
                ->setCity($center->getCity());

            if($form->isSubmitted() && $form->isValid())
            {
                $centerManager->insert($center);
                $em_api->persist($center_api);
                $em_api->flush();
                $this->addFlash('success', 'alert.center.created');
                return $this->redirectToRoute('admin_center_edit', array(
                    'id' => $center->getId()
                ));

            }
        } catch (\Exception $exception)
        {
            $this->addFlash('danger', $translator->trans($exception->getMessage(), array(
                '%code_length%' => Center::CODE_LENGTH
            )));
        }

        return $this->render('AppBundle:Admin:Center/store.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/center/{id}/edit", name="admin_center_edit")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Center $center, ObjectManager $objectManager)
    {
        $form = $this->createForm(CenterType::class, $center);
        try {
            $em_api = $this->getDoctrine()->getManager('api');
            $center_api = $em_api->getRepository('ApiBundle:Center')->findOneBy(['code' => $center->getCode()]);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                //Flush
                $objectManager->flush();
                if($center_api)
                {
                    $center_api->setName($center->getName())
                        ->setCode($center->getCode())
                        ->setAddress($center->getAddress())
                        ->setZipcode($center->getZipcode())
                        ->setCity($center->getCity())
                    ;
                    $em_api->flush();
                }
                $this->addFlash('success', 'alert.center.updated');
            }
        } catch (\Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }





        return $this->render('AppBundle:Admin:Center/store.html.twig', array(
            'form' => $form->createView(),
            'center' => $center
        ));
    }

    /**
     * @Route("/admin/center/{id}/delete", name="admin_center_delete")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET"})
     */
    public function deleteAction($id, CenterManager $centerManager)
    {
        try {

            $centerManager->delete($id);
            $this->addFlash('success', 'alert.center.deleted');
        } catch (\Exception $exception)
        {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->redirectToRoute('admin_center_list');

    }

}
