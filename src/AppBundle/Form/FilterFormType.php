<?php

namespace AppBundle\Form;

use AppBundle\Entity\Filter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('nameField')
            ->add('isPublished', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ]
            ])
            ->add('isFolded', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ]
            ])
            ->add('options', CollectionType::class, [
                'entry_type' => OptionType::class,
                'entry_options' => [
                    'label' => false,
                    'attr' => ['class' => 'option-box'],
                ],
                'by_reference' => false,
                'allow_delete' =>true,
                'allow_add' => true,
                'label'  => false,
                'constraints' => new NotBlank(['message' => 'Options don\'t have to be empty']),
                'error_bubbling' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
        ]);
    }
}
