<?php

namespace App\Form;

use App\Entity\Vendeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VendeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pays')
            ->add('ville')
            ->add('tel')
            ->add('nom')
            ->add('type')
            ->add('heure_ouverture')
            ->add('heure_fermeture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vendeur::class,
        ]);
    }
}
