<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 27/11/2017
 * Time: 22:01
 */

namespace AppBundle\Event\Listener;

use AppBundle\Event\PasswordEvent;
use AppBundle\Event\SecurityEvent;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class RequestPasswordListener
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

    public function __construct(
        $from,
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
     * @param PasswordEvent $event
     */
    public function onRequestPassword(PasswordEvent $event)
    {
        $requestPassword = $event->getRequestPassword();
        $user = $event->getUser();
        $message = new \Swift_Message($this->translator->trans('Request forgotten password'));
        $message->setFrom($this->from)
                ->setTo($user->getEmail())
                ->setBody(
                    $this->templating->render('AppBundle:Mail:forgotten.html.twig', array(
                  'requestPassword' => $requestPassword,
                    'user' => $user
                )),
                  'text/html'
                );
        $this->mailer->send($message);
    }

    /**
     * @param SecurityEvent $event
     */
    public function onChangePassword(SecurityEvent $event)
    {
        $user = $event->getUser();
        $message = new \Swift_Message($this->translator->trans('Password changed'));
        $message->setFrom($this->from)
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('AppBundle:Mail:new-password.html.twig', array(
                'user' => $user
            )),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
