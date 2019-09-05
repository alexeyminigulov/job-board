<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User\Role;
use AppBundle\Entity\User\User;
use AppBundle\Form\EmployeeSignup\Data;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="employee")
 */
class Employee
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

    public function __construct(string $username, string $email, string $password)
    {
        $user = new User($username, $email, $password, Role::ROLE_EMPLOYEE);
        $this->user = $user;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }
}