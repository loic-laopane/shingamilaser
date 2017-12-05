<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * OfferRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OfferRepository extends \Doctrine\ORM\EntityRepository
{

    public function getActiveOffers()
    {
        return $this->createQueryBuilder('o')
            ->where('o.expiredAt > CURRENT_TIMESTAMP() ')
            ->getQuery()
            ->getResult();
    }

    public function getAllWithPage($page, $max)
    {
        $qr = $this->createQueryBuilder('o')
            ->setFirstResult($max * ($page - 1))
            ->setMaxResults($max);

        return new Paginator($qr);
    }

    public function countAll()
    {
        return $this->createQueryBuilder('o')
                ->select('COUNT(o)')
                ->getQuery()
                ->getSingleScalarResult();
    }
}
