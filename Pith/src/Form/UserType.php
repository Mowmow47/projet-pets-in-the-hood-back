<?php

namespace App\Form;

Use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('firstname')
            ->add('lastname')
            ->add('address')
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {

                $user = $event->getData();
                $form = $event->getForm();

                if ($user->getId() === null) {
                    $required = true;
                } else {
                    $required = false;
                }
                $form->add('password', PasswordType::class, [
                    'mapped' => false,
                    'required' => $required,
                ]);
            })
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
