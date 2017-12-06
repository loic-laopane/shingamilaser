<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2017
 * Time: 11:46
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Center;
use AppBundle\Entity\Offer;
use Doctrine\Common\Persistence\ObjectManager;

class OfferManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    private $repository;


    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository(Center::class);
    }

    /**
     * @param Offer $offer
     * @return $this
     */
    public function save(Offer $offer)
    {
        if (!$this->objectManager->contains($offer)) {
            $this->objectManager->persist($offer);
        }
        $this->objectManager->flush();

        return $this;
    }
}
