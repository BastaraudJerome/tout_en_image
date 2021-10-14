<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AvatarType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatar', AvatarType::class)
            ->add('pseudo')
            ->add('name')
            ->add('firstname')
            ->add('country', CountryType::class)
            ->add('email', EmailType::class)
            ->add('role', ChoiceType::class, ['choices' => [
                'Administrateur' => "ROLE_ADMIN",
                'Association' => "ROLE_ASSO",
                'Utilisateur'    => "ROLE_USER"
                ]])
            ->remove('password')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
