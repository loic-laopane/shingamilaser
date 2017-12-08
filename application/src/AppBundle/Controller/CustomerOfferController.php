<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CustomerOffer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CustomerOfferController extends Controller
{
    /**
     * @Route("/staff/customeroffer/check", name="check_customer_offer")
     */
    public function checkOfferAction(Request $request, ObjectManager $objectManager)
    {
        $response = ['status' => 0, 'message' => '' ];
        $customerOfferId = $request->request->get('id');
        $customerOffer = $objectManager->getRepository(CustomerOffer::class)->find($customerOfferId);
        if ($customerOffer instanceof CustomerOffer) {
            $now = new \DateTime();
            $customerOffer->setUsedAt($now);
            $objectManager->flush();
            $response['status'] = 1;
            $response['message'] = 'Offer has been used';
            $response['usedAt'] = $now->format('d/m/Y');
        } else {
            $response['message'] = 'Offer can be user';
        }

        return $this->json($response);
    }
}
