<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 16:07
 */

namespace test\AppBundle\Manager;

use AppBundle\Entity\Offer;
use AppBundle\Manager\OfferManager;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

class OfferManagerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->objectManager = $this->createMock(ObjectManager::class);
    }

    public function testSaveOffer()
    {
        $offer = $this->createMock(Offer::class);
        $offerManager = new OfferManager($this->objectManager);
        $this->assertEquals($offerManager, $offerManager->save($offer));
    }
}