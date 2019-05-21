<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="options")
 * @UniqueEntity(
 *  fields={"name", "filter"},
 *  message="Name should be unique."
 * )
 */
class Option
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Filter", inversedBy="options")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filter;

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

    public function getFilter()
    {
        return $this->filter;
    }

    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;
    }
}