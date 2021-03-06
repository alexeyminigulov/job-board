<?php

namespace AppBundle\Form\Profile\User\Fio;

use Symfony\Component\Validator\Constraints as Assert;

class Data
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $username;

    public function __construct(string $name)
    {
        $this->username = $name;
    }
}