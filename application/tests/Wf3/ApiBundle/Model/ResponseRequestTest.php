<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\Wf3\ApiBundle\Model;

use PHPUnit\Framework\TestCase;
use Wf3\ApiBundle\Entity\CenterRequest;
use Wf3\ApiBundle\Model\ResponseRequest;


class ResponseRequestTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();

    }


    public function testGetId()
    {
        $response = new ResponseRequest();
        $this->assertNull($response->getId());
    }


    public function testSetRequestId()
    {
        $response = new ResponseRequest();
        $this->assertEquals($response, $response->setRequestId(1));
    }

    /**
     * Get requestId
     *
     * @return int
     */
    public function testGetRequestId()
    {
        $request_id = 1;
        $response = new ResponseRequest();
        $response->setRequestId($request_id);
        $this->assertEquals($request_id, $response->getRequestId());
    }


    public function testSetCenterRequest()
    {
        $centerRequest = $this->createMock(CenterRequest::class);
        $response = new ResponseRequest();
        $this->assertEquals($response, $response->setCenterRequest($centerRequest));
    }

    public function testGetCenterRequest()
    {
        $centerRequest = $this->createMock(CenterRequest::class);
        $response = new ResponseRequest();
        $response->setCenterRequest($centerRequest);
        $this->assertEquals($centerRequest, $response->getCenterRequest());
    }


}
