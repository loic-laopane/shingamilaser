<?php

namespace test\Wf3\ApiBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Wf3\ApiBundle\Entity\Center;
use Wf3\ApiBundle\Manager\CardManager;
use Wf3\ApiBundle\Manager\CenterRequestManager;
use Wf3\ApiBundle\Model\AbstractResponse;

class CenterRequestManagerTest
{

    private $objectManager;
    private $abstractResponse;
    private $center;

    public function __construct()
    {
        parent::__construct();

        $this->objectManager = $this->createMock(ObjectManager::class);
        $this->cardManager = $this->createMock(CardManager::class);
        $this->abstractResponse = $this->createMock(AbstractResponse::class);
        $this->center = $this->createMock(Center::class);
    }

    public function getData()
    {
        return array('center' => 123, 'quantity' => 1);
    }
    public function testCanRequest()
    {
        $repository = $this->getMockForAbstractClass(ObjectRepository::class);
        $repository->expects($this->any())->method('findOneBy')->with(['numero' => 123])->willReturn($this->center);
        $this->objectManager->expects($this->any())->method('getRepository')->willReturn($repository);

        $centerRequestManager = new CenterRequestManager($this->objectManager, $this->cardManager);

        $this->assertInstanceOf(AbstractResponse::class, $centerRequestManager->request($this->getData(), $this->abstractResponse));
    }
}