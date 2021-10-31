<?php

namespace App\Form;

use App\Entity\Order;
use App\Form\AdressOrderType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientName', TextType::class, array('label' => 'Nom', 'attr' => array(
                'placeholder' => 'Nom*'
            )))
            ->add('clientFirstName', TextType::class, array('label' => 'Prénom', 'attr' => array(
                'placeholder' => 'Prénom*'
            )))
            ->add('clientEmail', EmailType::class, array('label' => 'Email', 'attr' => array(
                'placeholder' => 'Email*'
            )))
            ->add('adress', AdressType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
