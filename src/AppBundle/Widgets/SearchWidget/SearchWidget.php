<?php

namespace AppBundle\Widgets\SearchWidget;

use AppBundle\Entity\Filter;
use Symfony\Component\HttpFoundation\Request;

class SearchWidget
{
    /**
     * @var Filter[]
     */
    private $filters;

    /**
     * @var FilterWrap[]
     */
    private $filterWrap = [];

    /**
     * @var QueryParam[]
     */
    private $queryParams;

    public function __construct(array $filters, Request $request)
    {
        $this->filters = $filters;
        $this->queryParams = $this->getQueryParams($request);

        $this->setFilterWrap();
    }

    public function getFilterWrap()
    {
        return $this->filterWrap;
    }

    private function setFilterWrap()
    {
        $this->filterWrap = [];

        foreach ($this->filters as $filter) {
            $this->filterWrap[] = new FilterWrap($filter, $this->queryParams);
        }
    }

    private function getQueryParams(Request $request)
    {
        $params = [];
        foreach ($this->filters as $filter) {

            $name = $filter->getNameField();
            if ($value = $request->get($name)) {
                $params[] = new QueryParam($name, $value);
            }
        }
        return $params;
    }
}