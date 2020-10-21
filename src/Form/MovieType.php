<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', UrlType::class,
                [
                    'help' => 'Format: https://www.youtube.com/embed/(your video id)',
                    'constraints' =>
                        [
                            new NotBlank(),
                            new Url(['message' => 'This url is not valid.']),
                            new Regex(
                                [
                                    'pattern' => '^https:\/\/www.youtube.com\/embed\/[a-zA-Z0-9-_]+^',
                                    'message' => 'Please enter a valid url.',
                                ]
                            ),
                        ],
                    'label' => false,
                    'attr' => [
                        'class' => 'form form-control',
                        'id' => 'movie'
                    ]

                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}


