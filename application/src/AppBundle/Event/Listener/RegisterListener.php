<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 27/11/2017
 * Time: 22:01
 */

namespace AppBundle\Event\Listener;

use AppBundle\Event\SecurityEvent;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class RegisterListener
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    private $from;
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct($from,
                                \Swift_Mailer $mailer,
                                EngineInterface $templating,
                                TranslatorInterface $translator
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->from = $from;
        $this->templating = $templating;
    }

    /**
     * @param SecurityEvent $event
     */
    public function onRegistration(SecurityEvent $event)
    {
        $customer = $event->getCustomer();
        $user = $event->getUser();
        $message = new \Swift_Message($this->translator->trans('title.registration.completed'));
        $message->setFrom($this->from)
                ->setTo($user->getEmail())
                ->setBody(
                    $this->templating->render('AppBundle:Mail:registration.html.twig', array(
                  'customer' => $customer,
                    'user' => $user
                )),
                  'text/html'
                );
        $this->mailer->send($message);
    }

    /**
     * @param SecurityEvent $event
     */
    public function onQuickRegistration(SecurityEvent $event)
    {
        $user = $event->getUser();
        $message = new \Swift_Message($this->translator->trans('title.account.completed'));
        $message->setFrom($this->from)
          ->setTo($user->getEmail())
          ->setBody(
            $this->templating->render('AppBundle:Mail:user-create.html.twig', array(
              'title' => 'title.account.create',
              'user' => $user
            )),
            'text/html'
          );
        $this->mailer->send($message);
    }
}
