<?php

namespace test\Wf3\ApiBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Wf3\ApiBundle\Entity\Center;
use Wf3\ApiBundle\Manager\CenterRequestManager;

class CenterRequestManagerTest extends TestCase
{
    public function testCanFindCenter()
    {
        $repository = $this->createMock(EntityRepository::class);
        $objectManager = $this->createMock(ObjectManager::class);
        $data = ['center' => '123'];
        $centerRequestManager = new CenterRequestManager($objectManager);
        $objectManager->expects($this->once())->method('getRepository')->with(Center::class)->willReturn($repository);
        $this->assertInstanceOf(Center::class, $centerRequestManager->response($data));
    }
}