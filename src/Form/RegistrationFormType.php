<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'attr'=>['class'=>'form form-control']
            ])
            ->add('lastName', null, [
                'attr'=>['class'=>'form form-control']
            ])
            ->add('username', null, [
                'attr'=>['class'=>'form form-control']
            ])
            ->add('email', EmailType::class, [
                'attr'=>['class'=>'form form-control']
            ])
            ->add('pathLogo', FileType::class, [
                'attr'=>['class'=>'form form-control'],
                'constraints' => [
                    new File(
                        [
                            'maxSize' => '1M',
                            'maxSizeMessage' => 'Your Picture is more bigger !',
                            'mimeTypes' =>
                                [
                                    'image/jpg',
                                    'image/jpeg',
                                    'image/png',
                                ],
                            'mimeTypesMessage' => 'Please upload a valid picture',
                        ]
                    )
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type'=>PasswordType::class,
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'options'=>['attr'=>['invalid_message' => 'The password fields must match.']]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'autocomplete' => 'off',
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ],
        ]);
    }
}
