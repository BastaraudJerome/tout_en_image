<?php

namespace App\Form;

use App\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserPhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('imageName')
            ->add('titre')
            ->add('description')
            ->add('imageFile', FileType::class, ["required" => false, "label"=>'Photo'])           
            ->remove('Ajouter', SubmitType::class, ['attr'=>["class"=>"btn btn-primary my-2"]])
            ->remove('users')
            ->remove('imageSize')
            ->remove('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
