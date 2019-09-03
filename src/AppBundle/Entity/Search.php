<?php

namespace AppBundle\Entity;

class Search
{
    const TITLE = 'name';

    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}