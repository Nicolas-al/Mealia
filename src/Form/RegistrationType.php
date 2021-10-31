<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AdressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array( 'attr' => array(
                'placeholder' => 'Adresse mail*'
            )))
            ->add('firstName', TextType::class, array('label' => 'Prenom', 'attr' => array(
                'placeholder' => 'Prénom*'
            )))
            ->add('lastName', TextType::class, array('label' => 'Nom', 'attr' => array(
                'placeholder' => 'Nom*'
            )))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe', 'attr' => array(
                    'placeholder' => 'Mot de passe*'
                )), 
                'second_options' => array('label' => 'Confirmation*', 'attr' => array(
                    'placeholder' => 'Confirmation*'
                ))
            , ))
            ->add('sex', ChoiceType::class, [ 'choices' => [
                'Monsieur' => 'homme',
                'Madame' => 'femme'
            ], 'expanded' => 'true']
            )
            ->add('phone', TelType::class, array('attr' => array(
                'placeholder' => 'Téléphone'
            )))
            ->add('adress', AdressType::class)
            ->add('dateOfBirth', BirthdayType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
