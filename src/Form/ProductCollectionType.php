<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\ProductCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ProductCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('zeroWaste')
            ->add('imageFile', FileType::class, [
                'required' => false,
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductCollection::class,
            'attr' => ['id' => 'product_collection']
        ]);
    }
}
