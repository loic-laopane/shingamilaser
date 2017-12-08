<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 16:07
 */

namespace test\AppBundle\Service;

use AppBundle\Service\Pagination;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{

    public function testGettersSetters()
    {

        $current_page = 2;
        $route = 'login';
        $maxResult = 10;
        $total = 21;

        $data = [
          'page' => $current_page,
          'page_count' => 3,
          'route' => $route
        ];

        $pagination = new Pagination($current_page, $total, $route, $maxResult);

        $this->assertEquals($pagination, $pagination->setCurrentPage($current_page));
        $this->assertEquals($current_page, $pagination->getCurrentPage());

        $this->assertEquals($pagination, $pagination->setRoute($route));
        $this->assertEquals($route, $pagination->getRoute());

        $this->assertEquals($pagination, $pagination->setMaxResult($maxResult));
        $this->assertEquals($maxResult, $pagination->getMaxResult());

        $this->assertEquals($pagination, $pagination->setTotal($total));
        $this->assertEquals($total, $pagination->getTotal());

        $this->assertEquals($data, $pagination->getPagination());
    }
}