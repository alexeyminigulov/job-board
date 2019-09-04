<?php

namespace AppBundle\Form\EmployerSignup\User;

use Symfony\Component\Validator\Constraints as Assert;

class Data
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $username;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $password;
}