<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="resumes")
 */
class Resume
{
    public const STATUS_DRAFT = false;
    public const STATUS_ACTIVE = true;

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
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    public function __construct(string $name, string $description, $salary, Employee $employee)
    {
        $this->name = $name;
        $this->description = $description;
        $this->isPublished = self::STATUS_DRAFT;
        $this->salary = (int)$salary;
        $this->employee = $employee;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function getEmployee()
    {
        return $this->employee;
    }
}