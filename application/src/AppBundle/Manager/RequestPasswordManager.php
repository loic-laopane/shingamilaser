<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 28/11/2017
 * Time: 09:56
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Customer;
use AppBundle\Entity\RequestPassword;
use AppBundle\Entity\User;
use AppBundle\Event\PasswordEvent;
use AppBundle\Event\SecurityEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RequestPasswordManager
{


    /**
     * @var ObjectManager
     */
    private $objectManager;

    private $repository;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * RequestPasswordManager constructor.
     * @param ObjectManager $objectManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(ObjectManager $objectManager,
                                EventDispatcherInterface $dispatcher)
    {
        $this->objectManager = $objectManager;

        $this->repository = $objectManager->getRepository('AppBundle:RequestPassword');
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param User $user
     */
    public function create(User $user)
    {
        $requestPassword = new RequestPassword();
        $requestPassword->setUser($user);
        $requestPassword->setToken($this->generateToken());

        $this->objectManager->persist($requestPassword);
        $this->objectManager->flush();


        $this->dispatcher->dispatch(PasswordEvent::FORGOTTEN, new PasswordEvent($requestPassword));
    }

    /**
     * @param int $nb
     * @return string
     */
    private function generateToken()
    {
        return sha1(uniqid(rand(), true)); //\OAuthProvider::generateToken($nb);

    }

    /**
     * @param $token
     * @return User
     * @throws \Exception
     */
    public function getUserByToken($token)
    {
        $requestPassword = $this->repository->getRequestByToken($token);
        if(!$requestPassword instanceof  RequestPassword)
        {
            throw new \Exception('Invalid token');
        }

        if($requestPassword->getExpiredAt() < new \DateTime())
        {
            throw new \Exception('Token is expired');
        }

        $user = $requestPassword->getUser();
        if(null === $user)
        {
            throw new \Exception('User not found');
        }
        return $user;
    }
}