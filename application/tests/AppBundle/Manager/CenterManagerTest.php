<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:10
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\Center;
use AppBundle\Manager\CenterManager;
use AppBundle\Repository\CenterRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;


class CenterManagerTest extends TestCase
{
    private $objectManager;


    public function setUp()
    {
        parent::setUp();

        $this->objectManager = $this->createMock(ObjectManager::class);
    }

    public function testCenterExists()
    {
        $codeCenter = 125;
        $center = $this->createMock(Center::class);
        $center->method('getCode')->willReturn($codeCenter);
        $repo = $this->createMock(CenterRepository::class);
        $repo->method('findOneBy')->with(['code' => $codeCenter])->willReturn($center);
        $this->objectManager->method('getRepository')->willReturn($repo);
        $centerManager = new CenterManager($this->objectManager);
        $this->assertInstanceOf(Center::class, $centerManager->exists($center));
    }

    public function testCanInsertCenter()
    {
        $codeCenter = 125;
        $center = $this->createMock(Center::class);
        $center->method('getCode')->willReturn($codeCenter);
        $repo = $this->createMock(CenterRepository::class);
        $repo->method('findOneBy')->with(['code' => $codeCenter])->willReturn(false);
        $this->objectManager->method('getRepository')->willReturn($repo);
        $centerManager = new CenterManager($this->objectManager);
        $this->assertEquals($centerManager, $centerManager->insert($center));
    }

    public function testCannotInsertCenter()
    {
        $codeCenter = 125;
        $center = $this->createMock(Center::class);
        $center->method('getCode')->willReturn($codeCenter);
        $repo = $this->createMock(CenterRepository::class);
        $repo->method('findOneBy')->with(['code' => $codeCenter])->willReturn($center);
        $this->objectManager->method('getRepository')->willReturn($repo);
        $centerManager = new CenterManager($this->objectManager);
        $this->expectException(\Exception::class);
        $centerManager->insert($center);
    }

    public function testCanDeleteCenter()
    {
        $id = 5;
        $center = $this->createMock(Center::class);
        $repo = $this->createMock(CenterRepository::class);
        $repo->method('find')->with($id)->willReturn($center);
        $this->objectManager->method('getRepository')->willReturn($repo);

        $centerManager = new CenterManager($this->objectManager);
        $this->assertEquals($centerManager, $centerManager->delete($id));
    }

    public function testCannotDeleteCenter()
    {
        $id = 5;
        $repo = $this->createMock(CenterRepository::class);
        $repo->method('find')->with($id)->willReturn(null);
        $this->objectManager->method('getRepository')->willReturn($repo);

        $centerManager = new CenterManager($this->objectManager);
        $this->expectException(\Exception::class);
        $centerManager->delete($id);
    }
}