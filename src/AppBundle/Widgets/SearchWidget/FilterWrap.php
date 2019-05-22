<?php

namespace AppBundle\Widgets\SearchWidget;

use AppBundle\Entity\Filter;
use AppBundle\Entity\Option;

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

    public function __construct(Filter $filter, array $queryParams)
    {
        $this->filter = $filter;
        $this->queryParams = $queryParams;

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
        $query = '&'. $this->filter->getNameField() .'='. $option->getName();

        foreach ($this->queryParams as $param) {

            if ($param->getName() !== $this->getNameField() || $param->getName() === SearchWidget::JOB_TITLE) {
                $query .= '&'. $param->getName() .'='. $param->getValue();
            }
        }

        return trim($query, '&');
    }

    private function isSelectedOption(Option $option): bool
    {
        foreach ($this->queryParams as $param) {

            if ($param->getName() === $this->filter->getNameField() && $param->getValue() === $option->getName()) {
                return true;
            }
        }
        return false;
    }
}