<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Project;
use App\Entity\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nom du projet',
                    'required' => true,
                    'attr' =>
                    [
                        'placeholder' => 'Nom du projet'
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
            ->add(
                'info_short',
                TextType::class,
                [
                    'label' => 'alt / title',
                    'required' => true,
                    'attr' =>
                    [
                        'placeholder' => 'Info pour alt et title'
                    ]
                ]
            )
            ->add(
                'info_long',
                TextareaType::class,
                [
                    'label' => 'Longue description',
                    'attr' =>
                    [
                        'placeholder' => 'Longue description',
                        'rows' => '5',
                        // 'cols' => '10'
                    ]
                ]
            )
             ->add(
                 'files',
                 FileType::class,
                 [
                     'data_class' => null,
                     'label' => 'Image',
//                     'required' => true,
                     'multiple' => true, // pour pouvoir uploader plusieurs fichiers
                     'mapped' => false, // pour pouvoir uploader plusieurs fichiers
                     'attr' => [
                         'placeholder' => 'Ajouter une ou plusieurs images',
                         'title' => 'ne remplace pas la/les image(s)'
                     ],
                 ]
             )
        ;

        $formModifier = function (FormInterface $form, Type $type = null) {
            $categories = null === $type ? [] : $type->getCategory();

            $form->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'required' => false,
                    'placeholder' => 'Choisir une catégorie',
                    'choices' => $categories,
                    'attr' =>
                    [
                        'style' => 'display:none'
                    ]
                ]
            );


        };

        $builder->addEventListener(
          FormEvents::PRE_SET_DATA,
          function(FormEvent $event) use ($formModifier) {
              $data = $event->getData();
              $formModifier($event->getForm(), $data->getType());
          }
        );

        $builder->get('type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $type = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $type);
            }
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
