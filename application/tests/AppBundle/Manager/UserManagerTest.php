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
use PHPUnit\Framework\TestCase;
use PHPUnit\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManagerTest extends TestCase
{
    private $objectManager;
    private $encoder;

    public function __construct()
    {
        parent::__construct();
        $this->objectManager = $this->getMockBuilder(ObjectManager::class)->getMock();
        $this->encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)->getMock();
    }
    public function testCanEncodeUserPassword()
    {
        $user = $this->getMockBuilder(User::class)->getMock();
        $userManager = new UserManager($this->objectManager, $this->encoder);

        $this->assertInstanceOf(UserManager::class, $userManager->encodeUserPassword($user));

        $this->expectException(\TypeError::class);
        $userManager->encodeUserPassword('test');
    }
}