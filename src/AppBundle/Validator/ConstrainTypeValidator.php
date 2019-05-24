<?php

namespace AppBundle\Validator;

use AppBundle\Entity\Filter;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Unique Entity Validator checks if one or a set of fields contain unique values.
 */
class ConstrainTypeValidator extends ConstraintValidator
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param object     $entity
     * @param Constraint $constraint
     *
     * @throws UnexpectedTypeException
     * @throws ConstraintDefinitionException
     */
    public function validate($entity, Constraint $constraint)
    {
        if (!$constraint instanceof ConstrainType) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UniqueEntity');
        }

        if (!\is_array($constraint->fields) && !\is_string($constraint->fields)) {
            throw new UnexpectedTypeException($constraint->fields, 'array');
        }

        if (!is_string($constraint->type)) {
            throw new UnexpectedTypeException($constraint->type, 'string');
        }

        $fields = (array) $constraint->fields;

        if (0 === \count($fields)) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        if (null === $entity) {
            return;
        }

        $type = $this->getType($entity, $constraint);

        foreach ($fields as $fieldName) {

            $value = $entity->{'get'. $fieldName}();

            if ($type === Filter::TYPE_TEXT && !preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
                $this->context->buildViolation(sprintf($constraint->message, 'string'))
                    ->atPath($fieldName)
                    ->setParameter('{{ string }}', $value)
                    ->addViolation();
            }
            else if ($type === Filter::TYPE_INT && !preg_match('/^[0-9]+$/', $value, $matches)) {
                $this->context->buildViolation(sprintf($constraint->message, 'integer'))
                    ->atPath($fieldName)
                    ->setParameter('{{ integer }}', $value)
                    ->addViolation();
            }
        }
    }

    private function getType($entity, Constraint $constraint): string
    {
        $type = explode('.', $constraint->type);
        if (2 !== \count($type)) {
            throw new ConstraintDefinitionException('type has to be determine with dot separator.');
        }
        list($subEntity, $property) = $type;
        $type = $entity->{'get'. $subEntity}()->{'get'. $property}();

        return $type;
    }
}