<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 14:27
 */

namespace AppBundle\Event\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class LoginListener implements ListenerInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        // TODO: Implement handle() method.
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !$this->tokenStorage->getToken()) {
            return;
        }

        //Redirection si connecté
        $route = $event->getRequest()->attributes->get('_route');
        if (in_array($route, $this->authorisedRoutes()) && $this->isUserLogged()) {
            $response = new RedirectResponse($this->router->generate('homepage'));
            $event->setResponse($response);
        }
    }

    /**
     * @return bool
     */
    private function isUserLogged()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $user instanceof UserInterface;
    }
    /**
     * @return array
     */
    private function authorisedRoutes()
    {
        return ['login', 'register'];
    }
}
