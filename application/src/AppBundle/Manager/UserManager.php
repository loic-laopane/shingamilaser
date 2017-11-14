<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:46
 */

namespace AppBundle\Manager;


use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    public function __construct(ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
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
}