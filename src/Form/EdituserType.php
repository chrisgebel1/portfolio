<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Pour le côté BackOffice uniquement
 *
 * Class EdituserType
 * @package App\Form
 */
class EdituserType extends AbstractType
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
                'role',
                ChoiceType::class,
                [
                    'label'=>'Rôle',
                    'choices'=>
                    [
                        'Admin'=>'ROLE_ADMIN',
                        'Utilisateur'=>'ROLE_USER'
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
