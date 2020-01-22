<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Pour le côté User uniquement
 *
 * Class InscriptionType
 * @package App\Form
 */
class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'pseudo',
                TextType::class,
                [
                    'label'=>'Pseudo'
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
                        'label'=>'Mot de passe'
                    ],
                    // options du second champs
                    'second_options'=>[
                        'label'=>'Confirmation du mot de passe'
                    ],
                    // message si les 2 champs n'ont pas la même valeur
                    'invalid_message'=>'La confirmation ne correspond pas au mot de passe'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['registration']
        ]);
    }
}
