<?php

namespace AppBundle\Repository;

/**
 * CustomerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CustomerRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByParams(array $params)
    {
        $qr =  $this->createQueryBuilder('c');
        $qr->leftJoin('c.cards', 'card', 'WITH', 'card.active = :active')
            ->setParameter('active', true)
            ->select('c, card')
            ;
        if (isset($params['numero']) && !empty($params['numero'])) {
            $qr->andWhere('card.numero = :numero')
                ->setParameter('numero', $params['numero']);
        }
        if (isset($params['nickname']) && !empty($params['nickname'])) {
            $qr->andWhere('c.nickname LIKE :nickname')
                ->setParameter('nickname', $params['nickname'].'%');
        }

        if (isset($params['lastname']) && !empty($params['lastname'])) {
            $qr->andWhere('c.lastname LIKE :lastname')
                ->setParameter('lastname', $params['lastname'].'%');
        }

        if (isset($params['firstname']) && !empty($params['firstname'])) {
            $qr->andWhere('c.firstname LIKE :firstname')
                ->setParameter('firstname', $params['firstname'].'%');
        }
        //return $qr->getQuery()->getSQL();
        return $qr->getQuery()->getResult();
    }

    public function findByNicknameLike($value)
    {
        return $this->createQueryBuilder('c')
            ->where('c.nickname LIKE :nickname')
            ->setParameter('nickname', '%'.$value.'%')
            ->getQuery()
            ->getResult();
    }
}
