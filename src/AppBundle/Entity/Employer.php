<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist", "remove" })
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

    public function __construct(User $user, Company $company)
    {
        $this->user = $user;
        $this->company = $company;
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