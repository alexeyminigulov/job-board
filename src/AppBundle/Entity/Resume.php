<?php

namespace AppBundle\Entity;

use AppBundle\Form\Resume\Data;
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

    public function __construct(Data $data, Employee $employee)
    {
        $this->name = $data->name;
        $this->description = $data->description;
        $this->isPublished = self::STATUS_DRAFT;
        $this->salary = (int)$data->salary;
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