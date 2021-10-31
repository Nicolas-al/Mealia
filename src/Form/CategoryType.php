<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
        $builder
            ->add('name')
            ->add('imageFile', FileType::class, [
                'required' => 'false'
            ])
            ->add('imageFileTwo', FileType::class, [
                'required' => 'false'
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class
            ])
            // ->add('type', ChoiceType::class, [
            //     'choices'  => [
            //         'Textile' => 'Textile',
            //         'Petite Papeterie' => 'Petite Papeterie',
            // ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);

    }
}
