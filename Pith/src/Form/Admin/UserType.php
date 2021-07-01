<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => new Email(),
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'Votre prénom doit avoir au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre prénom ne doit pas contenir plus de {{ limit }} caractères',
                    ])
                ],
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new Length([
                        'min' => 1,
                        'max' => 60,
                        'minMessage' => 'Votre nom doit avoir au moins {{ limit }} caractère',
                        'maxMessage' => 'Votre nom ne doit pas contenir plus de {{ limit }} caractères',
                    ])
                ],
            ])
            ->add('picture', FileType::class, [
                'required' => false,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
