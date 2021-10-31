<?php

namespace App\Form;

use App\Entity\Size;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SizeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sizeOne', null , array(
                'required' => false
            ))
            ->add('sizeTwo', null , array(
                'required' => false
            ))
            ->add('sizeThree', null , array(
                'required' => false
            ))
            ->add('stockSizeOne', IntegerType::class, array(
                'required' => false
            ))
            ->add('stockSizeTwo', IntegerType::class, array(
                'required' => false
            ))
            ->add('stockSizeThree', IntegerType::class, array(
                'required' => false
            ))
            ->add('priceSizeOne', IntegerType::class, array(
                'required' => false
            ))
            ->add('priceSizeTwo', IntegerType::class, array(
                'required' => false
            ))
            ->add('priceSizeThree', IntegerType::class, array(
                'required' => false
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Size::class,
        ]);
    }
}
