<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ProductCollection;
use App\Repository\CategoryRepository;
use App\Repository\CollectionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('price', PriceType::class)
            ->add('addInformation', AddInformationType::class)
            ->add('collection', EntityType::class, [
                'class' => ProductCollection::class,
                'choice_label' => 'name'
            ])
            ->add('image', ImageType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('stock');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
