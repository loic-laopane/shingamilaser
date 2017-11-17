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
        $encoder = $this->container->get('security.password_encoder');
        // TODO: Implement load() method.
        $admin = new User();
        $admin->setUsername('loic')
            ->setEmail('loic@wf3.com')
            ->setRoles(['ROLE_SUPERADMIN']);
        $password = $encoder->encodePassword($admin, 'loic');
        $admin->setPassword($password);
        $manager->persist($admin);

        $user = new User();
        $user->setUsername('aaa')
            ->setEmail('aaa@wf3.com')
            ->setRoles(['ROLE_USER']);
        $password = $encoder->encodePassword($user, 'aaaa');
        $user->setPassword($password);
        $manager->persist($user);

        $customer = new Customer();
        $customer->setFirstname('Loic')->setLastname('Lao')->setNickname('Lolo')->setUser($user);
        $manager->persist($customer);

        $center = new Center();
        $center->setCode('123')
                ->setName('Shishi')
                ->setCity('Pau');
        $manager->persist($center);

        $card = new Card();
        $card
            ->setCenter($center)
            ->setCustomer($customer)
            ->setChecksum(7)
            ->setNumero('1231234567')
            ->setActivatedAt(new \DateTime())
            ->setActive(true);

        $manager->persist($card);

        $game = new Game();
        $game->setTitle('Party One')->setCreatedAt(new \DateTime());
        $manager->persist($game);

        $manager->flush();


    }
}