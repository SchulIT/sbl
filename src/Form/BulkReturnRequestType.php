<?php

namespace App\Form;

use App\Entity\BookCopy;
use App\Entity\Borrower;
use App\Form\Type\BarcodeChoiceList;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class BulkReturnRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('copies', BarcodeChoiceList::class, [
                'label' => 'label.copies',
                'help' => 'select.copies'
            ])
            ->add('canCheckout', CheckboxType::class, [
                'label' => 'return.can_checkout.label',
                'help' => 'return.can_checkout.help',
                'required' => false
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'return.comment.label',
                'help' => 'return.comment.help',
                'required' => false
            ]);
    }
}