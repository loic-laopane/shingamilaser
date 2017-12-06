<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * PurchaseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PurchaseRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAll($requester)
    {
        return $this->createQueryBuilder('p')
            ->where('p.requester = :user')
            ->setParameter('user', $requester)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $requester
     * @param $page
     * @param $max
     * @return Paginator
     */
    public function getAllByUserWithPage($requester, $page, $max)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.requester = :user')
            ->setParameter('user', $requester)
            ->setFirstResult($max * ($page - 1))
            ->setMaxResults($max);

        return new Paginator($qb);
    }

    /**
     * @param $requester
     * @return mixed
     */
    public function countAllByUser($requester)
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.requester = :user')
            ->setParameter('user', $requester)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
