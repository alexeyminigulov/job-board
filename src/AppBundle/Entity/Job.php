<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="jobs")
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $salary;

    /**
     * @ORM\ManyToOne(targetEntity="Company")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function setIsPublished($isPublished): void
    {
        $this->isPublished = $isPublished;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function setSalary($salary): void
    {
        $this->salary = $salary;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;
    }
}