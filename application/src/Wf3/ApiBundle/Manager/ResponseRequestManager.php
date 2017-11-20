<?php
/**
 * Created by PhpStorm.
 * User: Loïc
 * Date: 18/11/2017
 * Time: 23:39
 */

namespace Wf3\ApiBundle\Manager;


use Wf3\ApiBundle\Entity\ResponseRequest;

class ResponseRequestManager
{
    /**
     * @var CenterRequestManager
     */
    private $centerRequestManager;

    public function __construct(CenterRequestManager $centerRequestManager)
    {
        $this->centerRequestManager = $centerRequestManager;
    }

    /**
     * @param array $data
     * @return ResponseRequest
     */
    public function response(array $data, $type='request')
    {
        return $this->centerRequestManager->checkData($data, new ResponseRequest());
    }

    /**
     * @param $message
     * @param int $status_code
     * @return ResponseRequest
     */
    public function exception($message, $status_code = ResponseRequest::STATUS_BAD_REQUEST) {
        $reponseRequest = new ResponseRequest();
        $reponseRequest->setStatusCode($status_code);
        $reponseRequest->setMessage($message);
        return $reponseRequest;
    }
}