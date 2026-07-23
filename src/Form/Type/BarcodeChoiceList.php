<?php

namespace App\Form\Type;

use App\Entity\BookCopy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BarcodeChoiceList extends AbstractType {

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefault('attr', [
            'data-select' => 'tom-select',
            'data-can-create' => 'true',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->addModelTransformer(new CallbackTransformer(
                function (array $valueAsArray): string {
                    $values = array_map(
                        fn(int $value): string => BookCopy::computeBarcodeId($value),
                        $valueAsArray
                    );

                    return implode(',', $valueAsArray);
                },
                function (string $valueAsString): array {
                    $values = array_map('trim', explode(',', $valueAsString));
                    $numericValues = array_filter($values, 'is_numeric');
                    return array_map('intval', $numericValues);
                }
            ));
    }

    public function getParent(): string {
        return TextType::class;
    }
}