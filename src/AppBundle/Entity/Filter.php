<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="filters")
 */
class Filter
{
    const TYPE_INT = 'integer';
    const TYPE_TEXT = 'text';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @Assert\NotBlank()
     *
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

    /**
     * @var Collection
     * @ORM\OneToMany(
     *     targetEntity="Option",
     *     mappedBy="filter",
     *     fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     * @Assert\Valid()
     */
    private $options;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    private $type = self::TYPE_TEXT;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

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

    /**
     * @return ArrayCollection|User[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option)
    {
        if ($this->options->contains($option)
            || $this->options->exists(function (int $key, Option $item) use ($option) {
                return $option->getLabel() === $item->getLabel();
            })) {
            return;
        }
        $option->setFilter($this);
        $this->options->add($option);
    }

    public function removeOption(Option $option)
    {
        if (!$this->options->contains($option)) {
            return;
        }
        $this->options->removeElement($option);
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param $type
     * @throws \Exception
     */
    public function setType($type): void
    {
        if (!in_array($type, [self::TYPE_TEXT, self::TYPE_INT])) {
            throw new \Exception('Type do not embedded');
        }
        $this->type = $type;
    }
}