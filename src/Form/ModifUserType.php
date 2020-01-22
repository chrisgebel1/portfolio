<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Pour le côté User uniquement
 *
 * Class ModifUserType
 * @package App\Form
 */
class ModifUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'pseudo',
                TextType::class,
                [
                    'label'=>'Pseudo',
//                    'help' => 'Veuillez saisir un nouveau pseudo',
                    'attr' =>
                    [
                        'placeholder' => 'Pseudo'
                    ]
                ]
            )
            ->add(
                'Modifier vos informations',
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
