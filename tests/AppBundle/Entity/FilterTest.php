<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Filter;
use AppBundle\Entity\Option;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testAddOptions()
    {
        $filter = new Filter('City', 'city');
        $option = $filter->addOption('Main', 'main');

        $this->assertContains($option, $filter->getOptions());
    }
}