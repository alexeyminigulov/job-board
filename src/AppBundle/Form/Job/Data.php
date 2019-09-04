<?php

namespace AppBundle\Form\Job;

use Symfony\Component\Validator\Constraints as Assert;

class Data
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $description;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $isPublished;

    /**
     * @var integer
     * @Assert\NotBlank()
     */
    public $salary;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $city;
}