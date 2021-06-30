<?php

namespace App\Form\Admin;

use App\Entity\Advert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('dateOfLoss', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'mapped' => false,
            ])
            ->add('dateOfDiscovery', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'mapped' => false
            ])
            ->add('isActive')
            ->add('isReported')
            ->add('picture', FileType::class, [
                'required' => false,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
