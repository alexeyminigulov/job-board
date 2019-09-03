<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Validator as AcmeAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="options")
 * @UniqueEntity(
 *  fields={"label", "filter"},
 *  message="Name should be unique."
 * )
 * @AcmeAssert\ConstrainType(
 *     fields={"value"},
 *     type="filter.type"
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
     * @Assert\NotBlank(message="Label should not be blank")
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * @Assert\NotBlank(message="Value should not be blank")
     * @ORM\Column(type="string")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Filter", inversedBy="options")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filter;

    public function __construct(string $label, string $value, Filter $filter)
    {
        $this->label = $label;
        $this->value = $value;
        $this->filter = $filter;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function isExist(string $label): bool
    {
        return $this->label == $label;
    }
}