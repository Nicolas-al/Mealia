<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderInfoSuppType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('giftCard', CheckboxType::class ,['label' => 'Cette commande est un cadeau, je souhaite qu\'elle soit accompagnée d\'une carte "Méalia" (supp. de 0.50 €) ',
            'required' => false])
            ->add('commentGiftCard', TextareaType::class, array(
                'label' => false , 
                'attr' => array('placeholder' => 'Tapez un petit mot ici...'),
                'required' => false))
            ->add('comment', TextareaType::class, ['label' => 'Si vous souhaitez nous laisser un commentaire concernant
            votre commande, veuillez remplir le champ ci-dessous :',
            'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
