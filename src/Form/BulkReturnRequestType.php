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

class BulkReturnRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('copies', BarcodeChoiceList::class, [
                'label' => 'label.copies',
                'help' => 'select.copies'
            ]);
    }
}