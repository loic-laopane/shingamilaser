<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\Wf3\ApiBundle\Entity;

use PHPUnit\Framework\TestCase;
use Wf3\ApiBundle\Entity\Card;
use Wf3\ApiBundle\Entity\Center;
use Wf3\ApiBundle\Entity\CenterRequest;
use Wf3\ApiBundle\Model\ResponseGetAll;


class ResponseGetAllTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();

    }


   public function testGetId()
   {
       $response = new ResponseGetAll();
       $this->assertNull($response->getId());
   }

    public function testGetCards()
    {
        $card = $this->createMock(Card::class);
        $data = [$card, $card];
        $response = new ResponseGetAll();
        $response->setCards($data);
        $this->assertEquals($data, $response->getCards());
    }

    public function testSetCard()
    {
        $card = $this->createMock(Card::class);
        $data = [$card, $card];
        $response = new ResponseGetAll();
        $this->assertEquals($response, $response->setCards($data));

    }


}
