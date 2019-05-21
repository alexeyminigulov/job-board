<?php

namespace AppBundle\Widgets\SearchWidget;

use AppBundle\Entity\Option;

class OptionWrap
{
    /**
     * @var Option
     */
    private $option;

    private $query = '';

    private $isSelected = false;

    public function __construct(Option $option, string $query, bool $isSelected = false)
    {
        $this->option = $option;
        $this->query = $query;
        $this->isSelected = $isSelected;
    }

    public function getName()
    {
        return $this->option->getName();
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getIsSelected(): bool
    {
        return $this->isSelected;
    }
}