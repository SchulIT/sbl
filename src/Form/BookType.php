<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('title', TextType::class, [
                'label' => 'label.title'
            ])
            ->add('subtitle', TextType::class, [
                'label' => 'label.subtitle',
                'required' => false
            ])
            ->add('barcodeTitle', TextType::class, [
                'label' => 'label.barcode_title.label',
                'help' => 'label.barcode_title.help',
                'required' => false
            ])
            ->add('publisher', TextType::class, [
                'label' => 'label.publisher',
                'required' => true
            ])
            ->add('isbn', TextType::class, [
                'label' => 'label.isbn'
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'label.comment',
                'required' => false
            ])
            ->add('cover', VichImageType::class, [
                'label' => 'label.image',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg'
                        ]
                    ])
                ]
            ]);
    }
}