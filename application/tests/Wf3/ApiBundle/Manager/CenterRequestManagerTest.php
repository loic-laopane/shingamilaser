<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 14:12
 */

namespace Wf3\ApiBundle\Manager;


use Doctrine\Common\Persistence\ObjectManager;
use JMS\Serializer\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Wf3\ApiBundle\Model\AbstractResponse;
use Wf3\ApiBundle\Model\ResponseRequest;

class CenterRequestManagerTest extends TestCase
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
        $this->abstractRespnse = $this->getMockBuilder(AbstractResponse::class)
            ->setMethods(['setCenterRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->objectManager = $this->createMock(ObjectManager::class);
        $this->cardManager = $this->createMock(CardManager::class);
    }

   public function testCenterFieldMissingInRequest() {

        $data = [];
        $centerRequestManager = new CenterRequestManager($this->objectManager, $this->cardManager);
        $this->expectException(\Exception::class);
        $centerRequestManager->request($data, $this->abstractRespnse);
   }

    public function testGoodRequest() {

        $data = ['center' => 123];
        $centerRequestManager = new CenterRequestManager($this->objectManager, $this->cardManager);
        $this->expectException(\Exception::class);
        $this->assertInstanceOf(ResponseRequest::class, $centerRequestManager->request($data, $this->abstractRespnse));
    }

    public function testRequestIdFieldMissingInGetAllRequest() {
        $data = ['center' => 123];
        $centerRequestManager = new CenterRequestManager($this->objectManager, $this->cardManager);
        $this->expectException(\Exception::class);
       $centerRequestManager->getAll($data, $this->abstractRespnse);
    }

    public function testCheckedEntries()
    {
        $accept_array = ['field_1', 'field_2'];
        $data_array = ['field_1' => 'val 1', 'field_2' => 'val 2'];
        $centerRequestManager = new CenterRequestManager($this->objectManager, $this->cardManager);

        $this->assertInstanceOf(CenterRequestManager::class, $centerRequestManager->checkEntries($data_array, $accept_array));
    }

    public function testCheckedMissingEntries()
    {
        $accept_array = ['field_1', 'field_2'];
        $data_array = ['field_1' => 'val 1', 'field_3' => 'val 2'];
        $centerRequestManager = new CenterRequestManager($this->objectManager, $this->cardManager);
        $this->expectException(\Exception::class);
        $centerRequestManager->checkEntries($data_array, $accept_array);
    }

    public function testCheckedUnknownedEntries()
    {
        $accept_array = ['field_1', 'field_2'];
        $data_array = ['field_1' => 'val 1', 'field_2' => 'val 2', 'field_3' => 'val_3'];
        $centerRequestManager = new CenterRequestManager($this->objectManager, $this->cardManager);
        $this->expectException(\Exception::class);
        $centerRequestManager->checkEntries($data_array, $accept_array);
    }



    public function testGetAllRequest() {

        $data = ['request_id' => 1, 'center' => 123];
        $centerRequestManager = new CenterRequestManager($this->objectManager, $this->cardManager);
        $this->expectException(\Exception::class);
        $centerRequestManager->getAll($data, $this->abstractRespnse);
    }

}