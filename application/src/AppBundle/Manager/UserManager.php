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
use AppBundle\Event\PasswordEvent;
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
     * @var ObjectRepository
     */
    private $repository;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(
        ObjectManager $manager,
                                UserPasswordEncoderInterface $encoder,
                                EventDispatcherInterface $dispatcher
    ) {
        $this->manager = $manager;
        $this->encoder = $encoder;
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
        return $this->repository->findOneBy(['username' => $user->getUsername()]);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function mailExists(User $user)
    {
        return $this->repository->findOneBy(['email' => $user->getEmail()]);
    }

    /**
     * Insert User in DB
     * @param User $user
     * @return $this
     * @throws \Exception
     */
    public function insert(User $user)
    {
        if ($this->exists($user)) {
            throw new \Exception('This User already exists');
        }

        if ($this->mailExists($user)) {
            throw new \Exception('This Email is already used');
        }

        $plainUser = clone $user;
        $this->encodeUserPassword($user);
        $this->manager->persist($user);
        $this->manager->flush();

        $securityEvent = new SecurityEvent();
        $securityEvent->setUser($plainUser);
        $this->dispatcher->dispatch(SecurityEvent::CREATE_USER, $securityEvent);

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function save(User $user)
    {
        if (!$this->manager->contains($user)) {
            $this->manager->persist($user);
        }
        $this->manager->flush();

        return $this;
    }

    /**
     * @param $id
     * @return $this
     * @throws \Exception
     */
    public function delete($id)
    {
        $user = $this->repository->find($id);
        if (null === $user) {
            throw new \Exception('This User doesn\'t exist');
        }

        $username = $user->getUsername();
        $this->manager->remove($user);
        $this->manager->flush();

        return $this;
    }

    /**
     * @param $email
     * @return User
     * @throws \Exception
     */
    public function getUserByEmail($email)
    {
        if (empty($email)) {
            throw new \Exception('Email required');
        }

        $user = $this->repository->findOneBy(['email' => $email]);
        if (null === $user) {
            throw new \Exception('User not found with email '.$email);
        }
        return $user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function changePassword(User $user)
    {
        $plainUser = clone $user;
        $this->encodeUserPassword($user);
        $this->save($user);

        $securityEvent = new SecurityEvent();
        $securityEvent->setUser($plainUser);

        $this->dispatcher->dispatch(SecurityEvent::CHANGE_PASSWORD, $securityEvent);

        return $this;
    }
}
