<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ContactType;
use AppBundle\Model\Contact;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

class AppController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle:App:index.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request, ObjectManager $objectManager, \Swift_Mailer $mailer, EngineInterface $templating)
    {
        $contact = new Contact();
        $admins = $objectManager->getRepository(User::class)->getAdminsMails();;
        $mails_to = [];
        foreach($admins as $admin_mail)
        {
            $mails_to[] = $admin_mail['email'];
        }
        $form = $this->createForm(ContactType::class, $contact);
        try {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $message = new \Swift_Message('Contact');
                $message->setFrom($this->getParameter('mailer_sender_address'))
                        ->setTo($this->getParameter('mailer_sender_address'))
                        ->setBody($templating->render('AppBundle:Mail:contact.html.twig', array(
                      'contact' => $contact
                    )),
                      'text/html');
                $mailer->send($message);
                $form = $this->createForm(ContactType::class, new Contact());
                $this->addFlash('success', 'alert.message.sent');
            }

        } catch (\Exception $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->render('AppBundle:App:contact.html.twig', array(
            'form' =>$form->createView()
        ));
    }
}
