<?php

namespace App\Form;

use App\Form\SizeType;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Type;
use App\Entity\ProductCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, array("attr" => array("cols" => "6", "rows" => "4")))
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name'
            ])
            ->add('collection', EntityType::class, [
                'class' => ProductCollection::class,
                'choice_label' => 'name'
            ])
            ->add('image', ImageType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('size', SizeType::class)

            ->add('text', TextareaType::class, array("attr" => array("cols" => "6", "rows" => "4")))
            ->add('online', CheckboxType::class, array(
                'required' => false
            ))
            ->add('dimensions');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'attr' => ['id' => 'product_form']
        ]);
    }
}
