<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CustomerController
 * @package AppBundle\Controller
 * @Route("/customer")
 */
class CustomerController extends Controller
{
    /**
     * @Route("/search", name="customer_search")
     */
    public function searchAction(Request $request, ObjectManager $objectManager)
    {
        $term = $request->request->get('term');
        $customers = $objectManager->getRepository(Customer::class)->findByNicknameLike($term);
        $return = [];
        foreach($customers as $customer)
        {
            $return[] = $customer->getNickname();
        }
        return $this->json($return);
    }

}
