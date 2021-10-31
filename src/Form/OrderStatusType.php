<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderStatusType extends AbstractType
{
    private $name = 'order_status';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Delivery', DeliveryStatusType::class);
    }

    public function setName($name){
        $this->name = $name;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        

        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
