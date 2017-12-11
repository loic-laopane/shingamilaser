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

class LocaleListener implements ListenerInterface
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
        $request = $event->getRequest();
        $path = $request->getPathInfo();
        //dump($path); die;

        $route_exists = false; //by default assume route does not exist.
        $routeName = null;

        foreach ($this->router->getRouteCollection() as $routeObject) {
            $routePath = $routeObject->getPath();
            if ($routePath == "/{_locale}".$path) {
                $route_exists = true;
                dump($path);
                die;
                break;
            }
        }

        //If the route does indeed exist then lets redirect there.
        if ($route_exists == true) {
            //Get the locale from the users browser.
            $locale = $request->getDefaultLocale();

            //If no locale from browser or locale not in list of known locales supported then set to defaultLocale set in config.yml
            if ($locale=="") {
                $locale = $request->getDefaultLocale();
            }

            $event->setResponse(new RedirectResponse($locale.$path));
        }
    }
}
