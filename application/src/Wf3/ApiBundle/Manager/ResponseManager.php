<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 18/11/2017
 * Time: 23:39
 */

namespace Wf3\ApiBundle\Manager;


use JMS\Serializer\Serializer;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wf3\ApiBundle\Model\AbstractResponse;
use Wf3\ApiBundle\Model\ResponseException;


class ResponseManager
{
    /**
     * @var CenterRequestManager
     */
    private $centerRequestManager;
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(CenterRequestManager $centerRequestManager, Serializer $serializer)
    {
        $this->centerRequestManager = $centerRequestManager;
        $this->serializer = $serializer;
    }

    /**
     * @param array $data
     * @return Response
     */
    public function response(Request $request, $method='request')
    {

        if(!method_exists($this->centerRequestManager, $method))
        {
            throw new Exception('Error method response');
        }
        $class_name = 'Response'.ucfirst(strtolower($method));
        $class = "Wf3\\ApiBundle\\Model\\".$class_name;
        if(!class_exists($class))
        {
            throw new Exception('Class '.$class.' not found');
        }

        $data = $this->serializer->deserialize($request->getContent(), 'array', 'json');
        $myResponse = $this->centerRequestManager->$method($data, new $class());

        return $this->makeResponse($myResponse);
    }

    /**
     * @param AbstractResponse $abstractResponse
     * @return Response
     */
    private function makeResponse(AbstractResponse $abstractResponse)
    {
        $jsonResponse = $this->serializer->serialize($abstractResponse, 'json');
        //Creation et envoi d'une reponse
        $response = new Response();
        $response->setContent($jsonResponse);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param $message
     * @param int $status_code
     * @return Response
     */
    public function exception($message) {
        $reponse = new ResponseException();
        $reponse->setMessage($message);
        $reponse->setStatusCode(AbstractResponse::STATUS_BAD_REQUEST);
        return $this->makeResponse($reponse);
    }
}