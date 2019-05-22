<?php

namespace AppBundle\Widgets\SearchWidget;

use AppBundle\Entity\Filter;
use Symfony\Component\HttpFoundation\Request;

class SearchWidget
{
    const JOB_TITLE = 'title';

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
        $this->queryParams = $this->setQueryParams($request);

        $this->setFilterWrap();
    }

    public function getFilterWrap()
    {
        return $this->filterWrap;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    private function setFilterWrap()
    {
        $this->filterWrap = [];

        foreach ($this->filters as $filter) {
            $this->filterWrap[] = new FilterWrap($filter, $this->queryParams);
        }
    }

    private function setQueryParams(Request $request)
    {
        $params = [];
        foreach ($this->filters as $filter) {

            $name = $filter->getNameField();
            if ($value = $request->get($name)) {
                $params[] = new QueryParam($name, $value, $filter->getType());
            }
        }
        if ($value = $request->get(self::JOB_TITLE)) {
            $params[] = new QueryParam(self::JOB_TITLE, $value, Filter::TYPE_TEXT);
        }

        return $params;
    }
}