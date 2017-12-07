<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\Wf3\ApiBundle\Entity;

use PHPUnit\Framework\TestCase;
use Wf3\ApiBundle\Entity\ResponseException;


class ResponseExceptionTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();

    }

    public function testGetId()
    {
        $response = new ResponseException();
        $this->assertNull($response->getId());
    }
}
