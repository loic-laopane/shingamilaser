<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\Wf3\ApiBundle\Entity;

use PHPUnit\Framework\TestCase;
use Wf3\ApiBundle\Entity\CenterRequest;
use Wf3\ApiBundle\Entity\ResponseRequest;


class ResponseRequestTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();

    }

    public function testGettersSetters()
    {
        $requestId = 1;
        $centerRequest = $this->createMock(CenterRequest::class);

        $responseRequest = new ResponseRequest();

        $this->assertNull(null, $responseRequest->getId());

        $this->assertEquals($responseRequest, $responseRequest->setRequestId($requestId));
        $this->assertEquals($requestId, $responseRequest->getRequestId());

        $this->assertEquals($responseRequest, $responseRequest->setCenterRequest($centerRequest));
        $this->assertEquals($centerRequest, $responseRequest->getCenterRequest());

    }


}
