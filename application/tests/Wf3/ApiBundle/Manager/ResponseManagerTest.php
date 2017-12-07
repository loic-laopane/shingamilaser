<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 14:12
 */

namespace Wf3\ApiBundle\Manager;


use JMS\Serializer\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseManagerTest extends TestCase
{
    private $centerRequestManager;
    private $serializer;
    private $request;

    public function setUp()
    {
        $this->centerRequestManager = $this->createMock(CenterRequestManager::class);
        $this->serializer = $this->createMock(Serializer::class);
        $this->request = $this->createMock(Request::class);
    }

    public function testResponse()
    {
        $method = 'request';
        $responseManager = new ResponseManager($this->centerRequestManager, $this->serializer);
        $this->assertInstanceOf(Response::class, $responseManager->response($this->request, $method));
    }

    public function testNoExistingMethodForResponse()
    {
        $method = 'fake_method';
        $responseManager = new ResponseManager($this->centerRequestManager, $this->serializer);
        $this->expectException(\Exception::class);
        $responseManager->response($this->request, $method);
    }

    public function testExceptionResponse()
    {
        $message = 'fake_message';
        $responseManager = new ResponseManager($this->centerRequestManager, $this->serializer);
        $this->assertInstanceOf(Response::class, $responseManager->exception($message));

    }
}