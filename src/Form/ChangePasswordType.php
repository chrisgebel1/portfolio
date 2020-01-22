<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Pour le côté User uniquement
 *
 * Class ChangePasswordType
 * @package App\Form
 */
class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'oldPassword',
                PasswordType::class,
                [
                    'label' => 'Ancien mot de passe',
                    'mapped' => false,
                    'attr' =>
                    [
                        'placeholder' => 'Votre ancien mot de passe'
                    ]
                ]
            )
            ->add(
                'plainpassword',
                // 2 champs qui doivent avoir la même valeur ...
                RepeatedType::class,
                [
                    // ... de type password
                    'type'=>PasswordType::class,
                    // options du 1er des 2 champs
                    'first_options'=>[
                        'label'=>'Nouveau mot de passe',
                        'attr' =>
                            [
                                'placeholder' => 'Votre nouveau mot de passe'
                            ]
                    ],
                    // options du second champs
                    'second_options'=>[
                        'label'=>'Confirmation du mot de passe',
                        'attr' =>
                            [
                                'placeholder' => 'Confirmer votre nouveau mot de passe'
                            ]

                    ],
                    // message si les 2 champs n'ont pas la même valeur
                    'invalid_message'=>'La confirmation ne correspond pas au nouveau mot de passe',
                    'required' => true
                ]
            )
            ->add(
                'Modifier le mot de passe',
                SubmitType::class,
                [
                    'attr'=>
                    [
                        'class' => 'btn btn-sm btn-primary btn-block w-auto mx-auto'
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
