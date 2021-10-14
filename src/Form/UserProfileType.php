<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AvatarType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatar', AvatarType::class, ["required"=>false])
            ->add('pseudo')
            ->add('name',)
            ->add('firstname')
            ->add('email')
            ->add('plainPassword', PasswordType::class, ["label"=> "Changer le mot de passe", "required" =>false ])
            ->add('confirmPassword', PasswordType::class, ["label"=>"Confirmer le mot de passe", "required" =>false])
            ->add('country', CountryType::class)           
            ->remove('Modifier', SubmitType::class, ['attr'=>["class"=>"btn btn-primary-edit my-2"]])
            ->remove('roles')
            ->remove('password')
            ->remove('isVerified')
           
            
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
