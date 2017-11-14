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

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // TODO: Implement load() method.
        $user = new User();
        $user->setUsername('loic')
            ->setPassword('loic')
            ->setEmail('loic@wf3.com')
            ->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();
    }
}