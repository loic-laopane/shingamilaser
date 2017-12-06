<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Game;

/**
 * PlayerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlayerRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCustomersByGame(Game $game)
    {
        return $this->createQueryBuilder('p')
            ->join('p.customer', 'c')
            ->select('p, c')
            ->where('p.game = :game')
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult();
    }

    public function getGamesCustomerWithCard(Customer $customer)
    {
        return $this->createQueryBuilder('p')
            ->join('p.customer', 'c')
            ->join('p.game', 'g')
            ->select('p, c')
            ->where('p.customer = :customer')
            ->setParameter('customer', $customer)
            ->andWhere('p.card IS NOT NULL')
            ->andWhere('g.startedAt IS NOT NULL')
            ->getQuery()
            ->getResult();
    }
}
