<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AdressOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom', 'attr' => array(
                'placeholder' => 'Nom*'
            )))
            ->add('firstName', TextType::class, array('label' => 'Nom', 'attr' => array(
                'placeholder' => 'Prénom*'
            )))
            ->add('country', CountryType::class, [ 
                'preferred_choices' => ['FR']
            ],)
            ->add('street', TextType::class, array('attr' => array(
                'placeholder' => 'Adresse*'
            )))
            ->add('adressSupplement', TextType::class, array('attr' => array(
                'placeholder' => 'Complément d\'adresse'),
                'required' => false,
                'empty_data' => ''
            ))
            ->add('zipCode', IntegerType::class, array('attr' => array(
                'placeholder' => 'Code Postal*'
            )))
            ->add('city', TextType::class, array('attr' => array(
                'placeholder' => 'Ville'
            )));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
