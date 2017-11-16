<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 09:53
 */

namespace app\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');
        // TODO: Implement load() method.
        $user = new User();
        $user->setUsername('loic')
            ->setEmail('loic@wf3.com')
            ->setRoles(['ROLE_SUPERADMIN']);
        $password = $encoder->encodePassword($user, 'loic');
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();
    }
}