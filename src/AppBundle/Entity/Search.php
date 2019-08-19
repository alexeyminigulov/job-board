<?php

namespace AppBundle\Entity;

class Search
{
    const TITLE = 'name';

    protected $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}