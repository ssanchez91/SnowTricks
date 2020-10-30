<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\Picture;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Valid;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('slug', null, [
//                'required' => 'required',
//                'label' => false,
//                'attr' => [
//                    'class' => 'form form-control',
//                    'id' => 'slug'
//                ]
//            ])
            ->add('name', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form form-control',
                    'id' => 'name'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form form-control',
                    'id' => 'description',
                    'rows' => 4
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form form-control',
                    'id' => 'category'
                ],
                'class'=> Category::class,
                'query_builder' => function (CategoryRepository $c) {
                    return $c->createQueryBuilder('c')
                        ->orderBy('c.name')
                        ->getFirstResult();
                },
                'choice_label' => 'name'
            ])
            ->add('mainPicture', PictureType::class, [
                'mapped'=> false,
                'by_reference'=> false,
                'constraints' => [
                    new valid()
                ]
            ])
            ->add('mainMovie', MovieType::class, [
                'mapped'=> false,
                'by_reference'=> false,
                'constraints' =>
                    [
                        new valid()
//                        new NotBlank(),
//                        new Url(['message' => 'This url is not valid.', 'groups' => 'personal_error']),
//                        new Regex(
//                            [
//                                'pattern' => '^https:\/\/www.youtube.com\/embed\/[a-zA-Z0-9-_]+^',
//                                'message' => 'Please enter a valid url.',
//                                'groups' => 'personal_error'
//                            ]
//                        ),
                    ],
            ])
            ->add('pictures', CollectionType::class, [
                'entry_type' => PictureType::class,
                'label' => '',
                'label_attr' => [
                    'class' => 'd-none'
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('movies', CollectionType::class, [
                'entry_type' => MovieType::class,
                'label' => '',
                'label_attr' => [
                    'class' => 'd-none'
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
            'validation_groups' => ['Default', 'personal_error'],
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ],
        ]);
    }
}
