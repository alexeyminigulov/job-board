<?php

namespace AppBundle\Widgets\SearchWidget;

use AppBundle\Entity\Filter;
use AppBundle\Entity\Search;
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

    public function __construct(array $filters, Request $request, Search $search)
    {
        $this->filters = $filters;
        $this->queryParams = $this->setQueryParams($request, $search);

        $this->setFilterWrap($request->getPathInfo());
    }

    public function getFilterWrap()
    {
        return $this->filterWrap;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    private function setFilterWrap(string $pathInfo)
    {
        $this->filterWrap = [];

        foreach ($this->filters as $filter) {
            $this->filterWrap[] = new FilterWrap($filter, $this->queryParams, $pathInfo);
        }
    }

    private function setQueryParams(Request $request, Search $search)
    {
        $params = [];
        foreach ($this->filters as $filter) {

            $nameField = $filter->getNameField();
            if ($value = $request->get($nameField)) {
                $params[] = new QueryParam($nameField, $value, $filter->getType());
            }
        }
        if ($value = $request->get($search::TITLE)) {
            $params[] = new QueryParam($search::TITLE, $search->getName(), Filter::TYPE_TEXT);
        }

        return $params;
    }
}