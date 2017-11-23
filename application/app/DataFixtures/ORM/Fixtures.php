<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 09:53
 */

namespace app\DataFixtures\ORM;

use AppBundle\Entity\Card;
use AppBundle\Entity\Center;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $api_manager = $this->container->get('doctrine.orm.api_entity_manager');
        $encoder = $this->container->get('security.password_encoder');
        // TODO: Implement load() method.
        $admin = new User();
        $admin->setUsername('admin')
            ->setEmail('loic@wf3.com')
            ->setRoles(['ROLE_ADMIN']);
        $password = $encoder->encodePassword($admin, 'admin');
        $admin->setPassword($password);
        $manager->persist($admin);

        $staff = new User();
        $staff->setUsername('staff')
            ->setEmail('staff@wf3.com')
            ->setRoles(['ROLE_STAFF']);
        $password = $encoder->encodePassword($staff, 'staff');
        $staff->setPassword($password);
        $manager->persist($staff);

        $user = new User();
        $user->setUsername('user')
            ->setEmail('aaa@wf3.com')
            ->setRoles(['ROLE_USER']);
        $password = $encoder->encodePassword($user, 'user');
        $user->setPassword($password);
        $manager->persist($user);

        $customer = new Customer();
        $customer->setFirstname('Loic')->setLastname('Lao')->setNickname('Lolo')->setUser($user);
        $manager->persist($customer);

        $center = new Center();
        $center->setCode('123')
                ->setName('Shishi')
                ->setAddress('Rue de Paris')
                ->setZipcode('69021')
                ->setCity('Bourgogne city');
        $manager->persist($center);

        $center_api = new \Wf3\ApiBundle\Entity\Center();
        $center_api->setCode($center->getCode())
                    ->setName($center->getName())
                    ->setAddress($center->getAddress())
                    ->setZipcode($center->getZipcode())
                    ->setCity($center->getCity());
        $api_manager->persist($center_api);

        $game = new Game();
        $game->setTitle('Party One')->setCreatedAt(new \DateTime());
        $manager->persist($game);

        $manager->flush();

        $api_manager->flush();


    }
}