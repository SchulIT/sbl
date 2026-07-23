<?php

namespace App\Form;

use App\Entity\BookCopy;
use App\Entity\Borrower;
use App\Form\Type\BarcodeChoiceList;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BulkCheckoutRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('borrower', EntityType::class, [
                'label' => 'label.borrower',
                'class'=> Borrower::class,
                'placeholder' => 'select.borrower',
                'attr' => [
                    'data-select' => 'tom-select'
                ],
                'choice_label' => fn(Borrower $borrower) => sprintf('[%d] %s, %s', $borrower->getBarcodeId(), $borrower->getLastname(), $borrower->getFirstname())
            ])
            ->add('copies', BarcodeChoiceList::class, [
                'label' => 'label.copies',
                'help' => 'select.copies'
            ]);
    }
}