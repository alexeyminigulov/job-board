<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="jobs")
 */
class Job
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
     * @ORM\Column(type="text")
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="Company")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    public function __construct(
        string $name, string $description,
        string $city, $salary, Company $company
    )
    {
        $this->name = $name;
        $this->description = $description;
        $this->isPublished = self::STATUS_DRAFT;
        $this->city = $city;
        $this->salary = (int)$salary;
        $this->company = $company;
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

    public function getCity()
    {
        return $this->city;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function getCompany()
    {
        return $this->company;
    }
}