<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:46
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use AppBundle\Event\SecurityEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ObjectRepository
     */
    private $repository;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(ObjectManager $manager,
                                UserPasswordEncoderInterface $encoder,
                                SessionInterface $session,
                                EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->session = $session;
        $this->repository = $this->manager->getRepository(User::class);
        $this->dispatcher = $dispatcher;
    }

    /**
     * Encode le mot de passe d'un utilisateur d'apres l'algorythme du security.yml
     * @param User $user
     * @return $this
     */
    public function encodeUserPassword(User $user)
    {
        $encodePassword = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodePassword);

        return $this;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function exists(User $user)
    {
        return $this->repository->findOneByUsername($user->getUsername());
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function mailExists(User $user)
    {
        return $this->repository->findOneByEmail($user->getEmail());
    }

    /**
     * Insert User in DB
     * @param User $user
     * @return bool
     */
    public function insert(User $user)
    {
        if($this->exists($user)) {
            $this->session->getFlashBag()->add('danger', 'This User already exists');
            return false;
        }

        if($this->mailExists($user)) {
            $this->session->getFlashBag()->add('danger', 'This Email is already used');
            return false;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        $this->session->getFlashBag()->add('success', 'User created');
        return true;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function save()
    {
        $this->manager->flush();

        return $this;
    }

    public function delete($id)
    {
        $user = $this->repository->find($id);
        if(null === $user)
        {
            $this->session->getFlashBag()->add('danger', 'This User doesn\'t exist');
            return false;
        }

        $username = $user->getUsername();
        $this->manager->remove($user);
        $this->manager->flush();

        $this->session->getFlashBag()->add('success', 'User '.$username.' has been deleted');
        return true;
    }

    /**
     * @param User $anonymous_user
     * @throws \Exception
     */
    public function sendRequestPassword(User $anonymous_user)
    {
        $user = $this->getUserByEmail($anonymous_user->getEmail());
        if(null === $user) {
            throw new \Exception('User not found with email '.$anonymous_user->getEmail());
        }

        $this->dispatcher->dispatch(SecurityEvent::FORGOTTEN, new SecurityEvent(new Customer(), $user));
    }

    /**
     * @param $email
     * @return User
     * @throws \Exception
     */
    public function getUserByEmail($email)
    {
        if(empty($email))
        {
            throw new \Exception('Email required');
        }

        $user = $this->repository->findOneBy(['email' => $email]);
        if(null === $user) {
            throw new \Exception('User not found with email '.$email);
        }
        return $user;
    }
}