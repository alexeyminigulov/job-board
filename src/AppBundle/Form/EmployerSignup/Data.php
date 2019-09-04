<?php

namespace AppBundle\Form\EmployerSignup;

use AppBundle\Form\EmployerSignup\User\Data as UserData;
use AppBundle\Form\EmployerSignup\Company\Data as CompanyData;

class Data
{
    public $user;
    public $company;

    public function __construct()
    {
        $this->user = new UserData();
        $this->company = new CompanyData();
    }
}