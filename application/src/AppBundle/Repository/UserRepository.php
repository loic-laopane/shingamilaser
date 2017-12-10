<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllWithPage($page, $max)
    {
        $qr = $this->createQueryBuilder('u')
            ->setFirstResult($max * ($page - 1))
            ->setMaxResults($max);

        return new Paginator($qr);
    }

    public function countAll()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAdminsMails()
    {
        return $this->createQueryBuilder('u')
            ->select('u.email')
          ->where('u.roles LIKE :role')
          ->setParameter('role', '%ROLE_ADMIN%')
          ->getQuery()
          ->getArrayResult();
    }
}
