<?php

namespace AppBundle\Widgets\SearchWidget;

use AppBundle\Entity\Filter;
use AppBundle\Entity\Option;
use AppBundle\Entity\Search;

class FilterWrap
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var OptionWrap[]
     */
    private $options = [];

    /**
     * @var QueryParam[]
     */
    private $queryParams = [];

    /**
     * @var string
     */
    private $pathInfo;

    public function __construct(Filter $filter, array $queryParams, string $pathInfo)
    {
        $this->filter = $filter;
        $this->queryParams = $queryParams;
        $this->pathInfo = $pathInfo;

        $this->init();
    }

    private function init()
    {
        foreach ($this->filter->getOptions() as $option) {

            $this->options[] = new OptionWrap(
                $option,
                $this->getQuery($option),
                $this->isSelectedOption($option)
            );
        }
    }

    public function getName()
    {
        return $this->filter->getName();
    }

    public function getNameField()
    {
        return $this->filter->getNameField();
    }

    public function getOptions()
    {
        return $this->options;
    }

    private function getQuery(Option $option): string
    {
        $query = '';

        if (!$this->isSelectedOption($option)) {
            $query .= '&'. $this->filter->getNameField() .'='. $option->getValue();
        }
        foreach ($this->queryParams as $param) {

            if ($param->getName() !== $this->getNameField() || $param->getName() === Search::TITLE) {
                $query .= '&'. $param->getName() .'='. $param->getValue();
            }
        }
        $query = trim($query, '&');

        return (strlen($query) > 0) ? '?'. $query : $this->pathInfo;
    }

    private function isSelectedOption(Option $option): bool
    {
        foreach ($this->queryParams as $param) {

            if ($param->getName() === $this->filter->getNameField() && $param->getValue() === $option->getValue()) {
                return true;
            }
        }
        return false;
    }
}