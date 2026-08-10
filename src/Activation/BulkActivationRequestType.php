<?php

namespace App\Activation;

use App\Form\Type\BarcodeChoiceList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BulkActivationRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('copies', BarcodeChoiceList::class, [
                'label' => 'label.copies',
                'help' => 'select.copies'
            ]);
    }
}