<?php

namespace AppBundle\Form\Resume;

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
     * @var bool
     * @Assert\NotBlank()
     */
    public $isPublished;

    /**
     * @var integer
     * @Assert\NotBlank()
     */
    public $salary;
}