<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\Wf3\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Wf3\ApiBundle\Entity\Card;
use Wf3\ApiBundle\Entity\Center;
use Wf3\ApiBundle\Entity\CenterRequest;


class CenterRequestTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();

    }

    public function testGettersSetters()
    {
        $quantity = 25;
        $createdAt = new \DateTime();
        $answeredAt = new \DateTime();
        $status = 'created';
        $center = $this->createMock(Center::class);
        $card = $this->createMock(Card::class);
        $cards = [$card];

        $centerRequest = new CenterRequest();

        $this->assertNull(null, $centerRequest->getId());

        $this->assertEquals($centerRequest, $centerRequest->setQuantity($quantity));
        $this->assertEquals($quantity, $centerRequest->getQuantity());

        $this->assertEquals($centerRequest, $centerRequest->setCreatedAt($createdAt));
        $this->assertEquals($createdAt, $centerRequest->getCreatedAt());

        $this->assertEquals($centerRequest, $centerRequest->setAnsweredAt($answeredAt));
        $this->assertEquals($answeredAt, $centerRequest->getAnsweredAt());

        $this->assertEquals($centerRequest, $centerRequest->setStatus($status));
        $this->assertEquals($status, $centerRequest->getStatus());

        $this->assertEquals($centerRequest, $centerRequest->setCenter($center));
        $this->assertEquals($center, $centerRequest->getCenter());

        $this->assertEquals($centerRequest, $centerRequest->addCard($card));
        $this->assertEquals($cards, $centerRequest->getCards());
    }


}
