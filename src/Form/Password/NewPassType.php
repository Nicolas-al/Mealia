<?php

namespace App\Form\Password;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class NewPassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options'  => array('label' => 'Nouveau mot de passe', 'attr' => array(
                'placeholder' => 'Nouveau mot de passe*'
            ),
            'label_attr' => array('class' => 'd-none'),
            ), 
            'second_options' => array('label' => 'Confirmation*', 'attr' => array(
                'placeholder' => 'Confirmation*'
            ),
            'label_attr' => array('class' => 'd-none'),
            ), 
        ))
        ->add('valider', SubmitType::class, [
            'attr' => ['class' => 'button-form'],
            'row_attr' => ['class' => 'text-center']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
