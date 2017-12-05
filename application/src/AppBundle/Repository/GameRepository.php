<?php

namespace AppBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * GameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GameRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $page
     * @param $max
     * @return Paginator
     */
    public function getListWithPage($page, $max)
    {
        $qb = $this->createQueryBuilder('g')
            ->setFirstResult($max * ($page - 1))
            ->setMaxResults($max);
        return new Paginator($qb);
    }

    /**
     * @return mixed
     */
    public function countAll()
    {
        return $this->createQueryBuilder('g')
            ->select('COUNT(g)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
