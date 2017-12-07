<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 13:22
 */

namespace test\Wf3\ApiBundle\Entity;

use PHPUnit\Framework\TestCase;
use Wf3\ApiBundle\Entity\Center;


class CenterTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();

    }

    public function testGettersSetters()
    {
        $name = 'Mon center';
        $code = 123;
        $address = 'fake adresse';
        $zipcode = 75632;
        $city = 'fake city';

        $center = new Center();

        $this->assertNull(null, $center->getId());

        $this->assertEquals($center, $center->setName($name));
        $this->assertEquals($name, $center->getName());

        $this->assertEquals($center, $center->setCode($code));
        $this->assertEquals($code, $center->getCode());

        $this->assertEquals($center, $center->setAddress($address));
        $this->assertEquals($address, $center->getAddress());

        $this->assertEquals($center, $center->setZipcode($zipcode));
        $this->assertEquals($zipcode, $center->getZipcode());

        $this->assertEquals($center, $center->setCity($city));
        $this->assertEquals($city, $center->getCity());
    }


}
