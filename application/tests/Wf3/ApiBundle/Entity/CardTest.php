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


class CardTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();

    }

    public function testGettersSetters()
    {
        $code = 123456;
        $numero = '123'.$code.'7';
        $createdAt = new \DateTime();
        $centerRequest = $this->createMock(CenterRequest::class);

        $card = new Card();

        $this->assertNull(null, $card->getId());

        $this->assertEquals($card, $card->setCode($code));
        $this->assertEquals($code, $card->getCode());

        $this->assertEquals($card, $card->setCreatedAt($createdAt));
        $this->assertEquals($createdAt, $card->getCreatedAt());

        $this->assertEquals($card, $card->setNumero($numero));
        $this->assertEquals($numero, $card->getNumero());

        $this->assertEquals($card, $card->setCenterRequest($centerRequest));
        $this->assertEquals($centerRequest, $card->getCenterRequest());

    }

    public function testGenerateNumero()
    {
        $codeCenter = 456;
        $codeCard = 987654;
        $modulo = 0;
        $center = $this->createMock(Center::class);
        $center->expects($this->any())->method('getCode')->willReturn($codeCenter);

        $centerRequest = $this->createMock(CenterRequest::class);
        $centerRequest->expects($this->any())->method('getCenter')->willReturn($center);

        $card = new Card();
        $card->setCode($codeCard)
            ->setCenterRequest($centerRequest);
        $card->generateNumero();
        $this->assertEquals($codeCenter.$codeCard.$modulo, $card->getNumero());
    }


}
