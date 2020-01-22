<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nom',
                    'attr' =>
                    [
                        'placeholder' => 'Nom de la catégorie'
                    ]
                ]
            )
            ->add(
                'type',
                EntityType::class,
                [
                    'class' => Type::class,
                    'required' => true,
                    'choice_label' => function (Type $type) {
                        return sprintf($type->getName());
                    },
                    'placeholder' => 'Choisir un type'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
