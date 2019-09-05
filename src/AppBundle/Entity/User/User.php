<?php

namespace AppBundle\Entity\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @UniqueEntity(fields={"username", "email"}, message="There is user with that email or username.")
 */
class User implements UserInterface
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_BLOCKED = 'blocked';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Employee", mappedBy="user")
     */
    private $employee;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Employer", mappedBy="user")
     */
    private $employer;

    private $plainPassword;

    public function __construct(string $username, string $email, string $password, $role = null)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->roles[] = Role::ROLE_USER;
        if ($role) {
            $this->roles[] = $role;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function getEmployer()
    {
        return $this->employer;
    }
}