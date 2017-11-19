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
    public function response(array $data)
    {
        return $this->centerRequestManager->checkData($data, new ResponseRequest());
    }
}