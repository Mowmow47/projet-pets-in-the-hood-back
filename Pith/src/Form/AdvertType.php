<?php

namespace App\Form;

use App\Entity\Advert;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            // TODO: Voir comment envoyer les dates au bon format sous insomnia pour tester le bon fonctionnement
            //->add('dateOfLoss', TextType::class)
            //->add('dateOfDiscovery', TextType::class)
            ->add('address')
            ->add('user')
            ->add('pet')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
