<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManagerTest
{
    private $objectManager;
    private $encoder;
    private $session;
    private $userManager;

    public function __construct()
    {
        parent::__construct();
        $this->objectManager = $this->getMockBuilder(ObjectManager::class)->getMock();

        $this->encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)->getMock();
        $this->session = $this->getMockBuilder(SessionInterface::class)->getMock();
        $this->userManager = $userManager = new UserManager($this->objectManager, $this->encoder, $this->session);
    }

    public function testCanEncodeUserPassword()
    {
        $user = $this->getMockBuilder(User::class)->getMock();

        $this->assertInstanceOf(UserManager::class, $this->userManager->encodeUserPassword($user));

        $this->expectException(\TypeError::class);

        $this->userManager->encodeUserPassword('test');
    }

    public function testCantCheckUserExist()
    {
        $this->expectException(\TypeError::class);
        $this->userManager->exists('not_an_user');
    }


    public function testCantCheckUserMailExist()
    {
        $this->expectException(\TypeError::class);
        $this->userManager->exists('not_an_user');
    }

    public function testCantInsertUser()
    {
        $user = $this->getMockBuilder(User::class)->getMock();

        $this->expectException(\TypeError::class);
        $this->userManager->insert('not_an_user');
    }

}
