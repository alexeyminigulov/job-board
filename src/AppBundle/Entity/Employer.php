<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User\Role;
use AppBundle\Form\EmployerSignup\Data;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="employer")
 */
class Employer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User\User", cascade={"persist", "remove" })
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Company", cascade={"persist", "remove" })
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $company;

    public function __construct($userName, $email, $password, $companyName, $description)
    {
        $this->user = new User(
            $userName,
            $email,
            $password,
            Role::ROLE_EMPLOYER
        );
        $this->company = new Company($companyName, $description);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getCompany()
    {
        return $this->company;
    }
}