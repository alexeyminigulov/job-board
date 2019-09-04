<?php

namespace AppBundle\Form\EmployerSignup\Company;

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
}