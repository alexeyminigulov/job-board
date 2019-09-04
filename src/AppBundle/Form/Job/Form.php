<?php

namespace AppBundle\Form\Job;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\TextType::class)
            ->add('description', Type\TextareaType::class)
            ->add('isPublished', Type\CheckboxType::class)
            ->add('salary', Type\IntegerType::class)
            ->add('city', Type\TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Data::class,
        ]);
    }
}