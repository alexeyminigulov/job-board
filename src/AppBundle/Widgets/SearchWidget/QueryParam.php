<?php

namespace AppBundle\Widgets\SearchWidget;

class QueryParam
{
    /**
     * @var string
     */
    private $name;

    private $value;

    /**
     * @var string
     */
    private $type;

    public function __construct(string $name, $value, string $type)
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getType()
    {
        return $this->type;
    }
}