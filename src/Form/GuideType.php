<?php

namespace App\Form;

use App\Entity\Guide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pays')
            ->add('ville')
            ->add('tel')
            ->add('age')
            ->add('sexe')
            ->add('nom')
            ->add('prenom')
            ->add('language')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Guide::class,
        ]);
    }
}
