<?php

namespace App\Form;

use App\Entity\Calander;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalanderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('date_begin', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('data_fin', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('Guide');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calander::class,
        ]);
    }
}
