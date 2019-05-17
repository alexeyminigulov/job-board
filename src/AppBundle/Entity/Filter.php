<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="filters")
 */
class Filter
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
     * @ORM\Column(type="string")
     */
    private $nameField;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFolded;

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

    public function getNameField()
    {
        return $this->nameField;
    }

    public function setNameField($nameField): void
    {
        $this->nameField = $nameField;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function setIsPublished($isPublished): void
    {
        $this->isPublished = $isPublished;
    }

    public function getIsFolded()
    {
        return $this->isFolded;
    }

    public function setIsFolded($isFolded): void
    {
        $this->isFolded = $isFolded;
    }
}