<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 14:12
 */

namespace Wf3\ApiBundle\Manager;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\Serializer\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wf3\ApiBundle\Entity\Center;
use Wf3\ApiBundle\Entity\CenterRequest;
use Wf3\ApiBundle\Model\AbstractResponse;
use Wf3\ApiBundle\Model\ResponseGetAll;
use Wf3\ApiBundle\Model\ResponseRequest;

class CardManagerTest extends TestCase
{
    private $centerRequestManager;
    private $serializer;
    private $request;
    private $objectManager;
    private $cardManager;
    private $abstractRespnse;

    public function setUp()
    {
        $this->centerRequestManager = $this->createMock(CenterRequestManager::class);
        $this->serializer = $this->createMock(Serializer::class);
        $this->request = $this->createMock(Request::class);
        //$this->abstractRespnse->expects($this->once())->method('setCenterRequest')->willReturn($this->abstractRespnse);
        $this->objectManager = $this->createMock(ObjectManager::class);
        $this->cardManager = $this->createMock(CardManager::class);
    }

   public function testCreate() {

        $centerRequest = $this->createMock(CenterRequest::class);
        $cardManager = new CardManager($this->objectManager);
        //$this->expectException(\Exception::class);
        $this->assertEquals($cardManager, $cardManager->create($centerRequest));
   }



}