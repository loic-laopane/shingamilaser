<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 05/12/2017
 * Time: 14:09
 */

namespace AppBundle\Service;


class Pagination
{

    private $current_page;
    private $total;
    private $maxResult;
    private $route;

    public function __construct($current_page, $total, $route, $maxResult = 10)
    {

        $this->current_page = $current_page;
        $this->total = $total;
        $this->maxResult = $maxResult;
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->current_page;
    }

    /**
     * @param mixed $current_page
     * @return Pagination
     */
    public function setCurrentPage($current_page)
    {
        $this->current_page = $current_page;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     * @return Pagination
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxResult()
    {
        return $this->maxResult;
    }

    /**
     * @param mixed $maxResult
     * @return Pagination
     */
    public function setMaxResult($maxResult)
    {
        $this->maxResult = $maxResult;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     * @return Pagination
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    public function getPagination()
    {
        return array(
            'page' => $this->getCurrentPage(),
            'page_count' => ceil($this->getTotal() / $this->maxResult),
            'route' => $this->getRoute()
        );
    }
}