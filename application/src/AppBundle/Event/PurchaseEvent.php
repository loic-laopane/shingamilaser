<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 21/11/2017
 * Time: 09:51
 */

namespace AppBundle\Event;

use AppBundle\Entity\Purchase;
use Symfony\Component\EventDispatcher\Event;

class PurchaseEvent extends Event
{
    const EVENT = 'purchase.event';

    /**
     * @var Purchase
     */
    private $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function getPurchase()
    {
        return $this->purchase;
    }
}
