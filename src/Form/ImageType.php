<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oneFile', FileType::class, [
                'required' => 'false'
            ])
            ->add('twoFile', FileType::class, [
                'required' => 'false'
            ])
            ->add('threeFile', FileType::class, [
                'required' => 'false'
            ])
            ->add('fourFile', FileType::class, [
                'required' => 'false'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
