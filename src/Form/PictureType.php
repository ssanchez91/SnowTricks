<?php

namespace App\Form;

use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', FileType::class, [
                'mapped'=>false,
                'label' => false,
                'label_attr' => ['class' => 'd-none'],
                'attr' => [
                    'class' => 'form form-control',
                    'id' => 'picture'
                ],
                'constraints' => [
                    new NotBlank(),
                    new File(
                        [
                            'maxSize' => '1024M',
                            'mimeTypes' =>
                                [
                                    'image/jpg',
                                    'image/jpeg',
                                    'image/png',
                                ],
                            'mimeTypesMessage' => 'Please upload a valid file',
                        ]
                    )
                ],
            ])
//            ->add('defaultPicture', CheckBoxType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
            'validation_class' => ['Default', 'personal_error'],
        ]);
    }
}
