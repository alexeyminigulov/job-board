<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="filters")
 */
class Filter
{
    public const STATUS_DRAFT = false;
    public const STATUS_ACTIVE = true;

    public const TYPE_INT = 'integer';
    public const TYPE_TEXT = 'text';
    public const TYPE_ARRAY = 'array';

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
     * TODO: Realize this property
     * @ORM\Column(type="boolean")
     */
    private $isFolded;

    /**
     * @var Option[]|ArrayCollection
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

    public function __construct(string $name, string $nameField)
    {
        $this->name = $name;
        $this->nameField = $nameField;
        $this->isPublished = self::STATUS_DRAFT;
        $this->isFolded = false;
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

    public function getNameField()
    {
        return $this->nameField;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function getIsFolded()
    {
        return $this->isFolded;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getOptions(): ArrayCollection
    {
        return $this->options;
    }

    /**
     * @param string $label
     * @param string $value
     * @return Option
     * @throws \Exception
     */
    public function addOption(string $label, string $value): Option
    {
        foreach ($this->options as $option) {
            if ($option->isExist($label)) {
                throw new \Exception('Option with that name already exists');
            }
        }
        $option = new Option($label, $value, $this);
        $this->options->add($option);

        return $option;
    }

    /**
     * @param Option $option
     * @throws \Exception
     */
    public function removeOption(Option $option)
    {
        foreach ($this->options as $key => $item) {
            if ($item->isExist($option->getLabel())) {
                $this->options->remove($key);
                return;
            }
        }
        throw new \Exception('Option with that name already exists');
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